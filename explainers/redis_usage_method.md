# How Redis Is Used In This Codebase

**Scope:** this is a static, read-only walkthrough of *how* Redis/caching is wired up and
where it's used — not a benchmark. For measured before/after numbers on the same subject,
see [`redis_performance_report.md`](redis_performance_report.md).

---

## 1. The short version

- Redis is used for **exactly one thing**: the Laravel `Cache` facade (`Cache::remember`,
  `Cache::get`/`put`, `Cache::many`/`putMany`). Nothing in the app calls the `Redis` facade
  or a raw Redis client directly.
- Redis is **not** used for sessions or queues — both default to the `database` driver
  (see [§5](#5-what-redis-is-not-used-for)).
- It's an **external Redis Cloud instance**, not a container in this stack — there's no
  `redis:` service in either `docker-compose.yml` or `docker-compose.prod.yml`. The app
  connects out to a hosted endpoint over `REDIS_HOST`/`REDIS_PORT`.
- Whether Redis is even in the loop is one env var: `CACHE_STORE`. Local dev
  ([`.env.example`](../.env.example)) defaults to `CACHE_STORE=database`; production
  ([`docker/env.gcp.example`](../docker/env.gcp.example)) sets `CACHE_STORE=redis`
  explicitly. `docker-compose.yml` (local Docker) deliberately stays on `file` so testing
  locally doesn't eat into the shared plan's 30-connection budget.
- Two call patterns exist side by side: a handful of controllers call `Cache::remember`
  directly, and a batching layer in [`Helper.php`](../app/Http/Helper/Helper.php) wraps
  `Cache::many`/`putMany` behind fail-safe helpers (`cacheManySafe`/`cachePutManySafe`) so a
  Redis outage degrades to plain database queries instead of an error.

---

## 2. Configuration layer

### 2.1 Cache store selection — [`config/cache.php`](../config/cache.php)

```php
'default' => env('CACHE_STORE', 'database'),
...
'redis' => [
    'driver' => 'redis',
    'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
    'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
],
```

The `redis` cache store doesn't open its own connection — it points at a named connection
(`cache`) defined in `config/database.php`'s `redis` block. That's standard Laravel, but
worth naming because it means **two separate Redis logical databases are configured**, not
one:

### 2.2 Redis connections — [`config/database.php`](../config/database.php)

```php
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    'options' => [
        'cluster' => env('REDIS_CLUSTER', 'redis'),
        'prefix'  => env('REDIS_PREFIX', ...),
    ],
    'default' => [ ...'database' => env('REDIS_DB', '0'), ... ],
    'cache'   => [ ...'database' => env('REDIS_CACHE_DB', '1'), ... ],
],
```

- `default` (Redis DB index 0) exists but nothing in the app currently uses it — there's no
  `Redis::connection('default')` call anywhere. It's provisioned, not exercised.
- `cache` (Redis DB index 1 by default) is what `Cache::` calls actually hit.
- **In production this distinction collapses**: `docker/env.gcp.example` sets both
  `REDIS_DB=0` and `REDIS_CACHE_DB=0`, with the comment *"The plan exposes database 0
  only — index 1 returns 'DB index is out of range'"*. So on the live Redis Cloud plan,
  both connections resolve to the same DB 0; the two-database split only exists in the
  config's shape, not in what's actually reachable.
- Every key, on any connection, gets prefixed — `REDIS_PREFIX` (database-level, defaults to
  `laravel_database_`) for raw Redis keys, and separately `CACHE_PREFIX` (defaults to
  `dook_` in prod per `docker/env.gcp.example`) for keys written through the `Cache` facade.
  These stack: a `Cache::put('foo', ...)` call ends up in Redis as
  `{REDIS_PREFIX}{CACHE_PREFIX}foo` in practice, so admin inspection of raw keys in
  `redis-cli` needs both prefixes to find application cache entries.

### 2.3 Client library: Predis, not phpredis

`REDIS_CLIENT` defaults to `phpredis` in [`config/database.php`](../config/database.php)
and [`.env.example`](../.env.example), but:

- The PHP `redis` extension is **not installed** in the Docker image — `Dockerfile`'s
  `install-php-extensions` line only adds `pdo_mysql mysqli bcmath zip opcache pcntl intl`.
- `composer.json` requires `predis/predis` (a pure-PHP client), and
  `docker/env.gcp.example` explicitly overrides `REDIS_CLIENT=predis`.

So the **default value in the config/example files is misleading** — the actual runtime
client, in every environment this repo can build, is Predis. This matters only if someone
changes `REDIS_CLIENT` back to `phpredis` without also installing the extension; the app
would then fail to connect to Redis at all (caught by the fallback logic in §3.2, so it
would degrade rather than crash — but silently).

### 2.4 Persistent connections — the load-bearing setting

```php
// config/database.php, both 'default' and 'cache' connections
'persistent' => env('REDIS_PERSISTENT', true),
'timeout' => env('REDIS_TIMEOUT', 2.0),
'read_write_timeout' => env('REDIS_READ_TIMEOUT', 2.0),
```

The comment directly above this block in the source explains why: opening a fresh
connection to the hosted Redis instance (TCP + AUTH) was measured at **~430ms**, versus
**~40ms** for an operation on an already-open connection. Without `persistent`, every
request would pay the ~430ms handshake and the cache would end up *slower* than the
database queries it replaces. So `persistent: true` is not a minor tuning knob here — it's
the difference between the cache being a net win or a net loss.

The trade-off is that a **persistent connection is held per php-fpm worker**, for the
worker's lifetime, not per-request. That turns "how many php-fpm workers can be busy at
once" into "how many Redis connections are open at once" — which is why the process pool
is deliberately capped:

- **[`docker/www.conf`](../docker/www.conf)**: `pm = ondemand`, `pm.max_children = 10`,
  `pm.process_idle_timeout = 30s`. `ondemand` matters specifically because workers (and
  their Redis connections) are only spawned under real traffic and are reaped after 30s
  idle — a `static`/`dynamic` pool would hold all 10 connections open permanently.
- **The budget these are sized against**: the Redis Cloud plan in use allows **30 client
  connections total**, across every container plus any manual `redis-cli` access. The math
  in `docker/www.conf`'s comments: 30 allowed − 5 reserved for headroom = 25 usable → 10
  per container leaves room for two containers.
- **What happens past the limit**: connection refusals are handled gracefully — see §3.2 —
  the request falls back to querying the database directly rather than erroring. The
  cost is that the cache silently "stops paying off" under enough concurrent load, with
  no visible failure.
- This is also explicitly called out as the reason the app is deployed as a single VM
  rather than an autoscaled service (`Dockerfile` comment): autoscaling would make the
  worker count — and therefore the Redis connection count — unbounded against a fixed
  30-connection plan.

### 2.5 Where Redis actually lives

There is no Redis container anywhere in this repo's Docker setup. `REDIS_HOST` in
production points at a **Redis Cloud** managed endpoint (`*.db.redis.io`), reached over the
VM's normal internet egress — not through the Cloud SQL VPN tunnel that the database
traffic uses (see the architecture diagram in
[`gcp_hosting_guide.md`](gcp_hosting_guide.md), which explicitly draws Redis Cloud as a
separate, direct-egress path from the VPN'd database path). Locally, `docker-compose.yml`
keeps `CACHE_STORE=file` by default specifically so that spinning up a dev container
doesn't consume connections from that same shared 30-connection budget; a comment there
says to flip it to `redis` only temporarily, when actually testing caching behaviour.

---

## 3. The batching + fail-safe layer — [`Helper.php`](../app/Http/Helper/Helper.php)

This is the more involved half of the caching design, and it's globally available: the
whole file is loaded as a Composer `files` autoload entry
(`composer.json` → `"files": ["app/Http/Helper/Helper.php"]`), not a PSR-4 class, so every
function in it (`Cache::remember` wrappers included) is a **bare global function**, callable
from any controller or Blade view without a `use` import.

### 3.1 Why it exists: N+1 counts, batched into `Cache::many`/`putMany`

Three call sites render **lists of cards** (POI attraction cards, tour-package cards) where
the naive per-item approach would run 2–6 small queries *per item, per request*:

| Helper | Called from | What it batches |
|---|---|---|
| `poiDepartureCounts()` | `PoiController::getRelatedPois()`, `DepertureController::packageDetails()` | Active/featured departure counts per POI |
| `poiDepartureCountsUnfiltered()` | `DestinationController` (related POIs) | Same, but unfiltered/duplicate-counting variant the destination page has always shown |
| `departureCardData()` | `PoiController::getDepartures()` | Attraction names, cheapest price, inclusion icons, and country name per departure card |

All three follow the same shape:

1. Build one Redis key per item (`poi_dep_counts_{id}`, `poi_dep_counts_all_{id}`,
   `dep_card_{id}`) — **keyed by the entity's own ID, not by page or query params**. This is
   deliberate: the same POI or departure appears on its own detail page, its destination's
   page, and every package/related listing that references it, so a per-entity key is
   reused across all of those call sites instead of being recomputed per page.
2. Fetch every key for the current page's items in **one round trip** via
   `Cache::many($keys)` (wrapped as `cacheManySafe`, see §3.2) — this is what turns N
   individual Redis `GET`s into a single `MGET`.
3. For whatever came back `null` (a real cache miss), run **one grouped SQL query** (`GROUP
   BY` + `whereIn`) covering every missing ID at once, instead of one query per ID.
4. Write all freshly-computed rows back in **one round trip** via `Cache::putMany()`
   (wrapped as `cachePutManySafe`).

TTLs: `poiDepartureCountsTtl()` returns **21,600s (6 hours)** — counts only change when a
departure is edited in the admin, so a long TTL is safe. `departureCardTtl()` returns
**60s**, chosen to match the TTL that pre-existing per-departure caches
(`departure_{id}_poi`, `departure_{id}_inclusions` — see §4) already used, so this doesn't
make admin edits appear on cards any slower than they did before this layer existed.

One deliberate omission: **signed image/asset URLs are never cached.** `departureCardData()`
caches only raw storage paths; `generateSignedUrl()` is called at render time by the
controller, every time, because a signed URL has its own expiry and caching an expired one
would silently break images.

### 3.2 The fail-safe wrapper: `cacheManySafe` / `cachePutManySafe`

```php
function cacheManySafe(array $keys) {
    if (!$keys || cacheMarkUnavailable()) {
        return $keys ? array_fill_keys($keys, null) : [];
    }
    try {
        return Cache::many($keys);
    } catch (\Throwable $e) {
        cacheMarkUnavailable(true);
        Log::warning('Cache read failed, falling back to the database: ' . $e->getMessage());
        return array_fill_keys($keys, null);
    }
}
```

Two things worth calling out:

- **Fallback, not failure.** If Redis is unreachable (connection refused, plan's 30-client
  cap hit, timeout) the `catch` block treats every requested key as a miss and returns
  `null`s. The three helpers in §3.1 then treat that exactly like a normal cache miss and
  fall through to the database query — same code path, no special-casing needed by the
  caller. A page keeps working; it just runs at pre-caching speed.
- **Per-request circuit breaker.** `cacheMarkUnavailable()` holds a `static` (function-local,
  not global-state) flag. The **first** Redis failure in a request sets it; every
  subsequent call to `cacheManySafe`/`cachePutManySafe` in that *same* request then skips
  the `try`/`catch` entirely and returns misses immediately, rather than paying the
  connection timeout (`REDIS_TIMEOUT=2.0s`) again for every remaining lookup on a page that
  might call these helpers several times. The flag is request-scoped only — PHP-FPM tears
  down all statics between requests — so the very next request retries Redis fresh. There
  is no cross-request circuit breaker (e.g. nothing in Redis or the DB records "Redis was
  down a moment ago, skip it for the next N seconds"); each new request starts optimistic.

`cachePutManySafe()` mirrors this on the write side — same static flag, same catch-and-log,
just discards the write instead of returning fallback data.

### 3.3 Simple single-key caching in the same file

A few other `Helper.php` functions use `Cache::remember()` directly (not the batching
layer), all with a flat **86,400s (24h) TTL**, for small, slow-changing reference lists
rendered on (almost) every page — the site's mega menu / footer data:

| Function | Cache key | Backing query |
|---|---|---|
| `getInclusionByName($names)` | `Inclusion_{md5(json_encode($names))}` | inclusion icons by name list |
| `getMegaMenuCountries()` | `mega_menu_countries` | top-16 countries for the mega menu |
| `getMegaMenuDestinations()` | `mega_menu_destinations` | top-16 destinations for the mega menu |
| `getMegaRegions()` | `mega_regions` | top-15 regions for the mega menu |
| `countries()` | `countries` | footer country list |
| `destinations()` | `destinations` | footer destination list |
| `experiences()` | `experiences` | experiences with a slug |
| `mega_regions_europe()` | `mega_regions_europe` | single "Europe" region row |
| `mega_regions_africa()` | `mega_regions_africa` | single "Africa" region row |

These go through the plain `Cache` facade with no fail-safe wrapper — an uncaught Redis
exception here would propagate normally (and, per `bootstrap/app.php`, ultimately surface
as a redirect to the homepage rather than a visible error — see the note in
[`redis_performance_report.md`](redis_performance_report.md), issue 2).

**Two functions in this same file have caching commented out, not implemented:**

```php
function getMegaExperience() {
    // return Cache::remember('mega_experience', 86400, function () {
        return DB::table('experiences')->where('status', 1)->where('exp_status', 1)...
    // });
}
function getMegaeServices() {
    // return Cache::remember('experiences', 86400, function () {
        return DB::table('experiences')->where('status', 1)->where('service_status', 1)...
    // });
}
```

Both are called **3 times each** from
[`resources/views/frontend/layouts/topbar.blade.php`](../resources/views/frontend/layouts/topbar.blade.php)
(the mega menu partial rendered on every page), so today that's 6 uncached queries on every
page load, site-wide. Note `getMegaeServices()`'s commented-out key would have been
`'experiences'` — the same key the unrelated `experiences()` function above already uses for
a *different* query — so simply uncommenting it as written would serve one function's rows
under the other's key. This is flagged, not fixed, in this write-up (it's the same
observation `redis_performance_report.md` makes in its issue 10).

---

## 4. Direct `Cache::` calls in controllers

Outside `Helper.php`, several controllers call the `Cache` facade inline, with no shared
wrapper. These are simpler, single-key `remember`/`get`/`put` calls:

| File | Cache key pattern | TTL | What's cached |
|---|---|---|---|
| [`DepertureController.php:43`](../app/Http/Controllers/Frontend/DepertureController.php#L43) | `international_tour_packages_{md5(...)}` (keyword+page+filters+price range) | 60s | Full paginated international tour query, with eager-loaded price/inclusions |
| [`DepertureController.php:97,118,281`](../app/Http/Controllers/Frontend/DepertureController.php#L97) | `departure_{id}_poi`, `departure_{id}_inclusions` | 60s | Per-departure POI names / inclusion icons — run **inside** a `foreach`, so still one Redis round trip per departure per key (this is the N+1 the batching layer in §3.1 was built to replace, but this controller — international and domestic listings alike — hasn't been migrated to `departureCardData()`) |
| [`DepertureController.php:131-151`](../app/Http/Controllers/Frontend/DepertureController.php#L131) | *(none)* | *(none)* | The departure → destination → country name walk in the **same loop** as the two cached lookups above — three uncached `DB::table()` calls per departure, every request. Notable because it sits right next to two `Cache::remember()` calls in the same iteration and isn't itself cached at all |
| [`DepertureController.php:161,327`](../app/Http/Controllers/Frontend/DepertureController.php#L161) | `departure_page_header`, `departure_page_header_domestic` | 60s | Landing-page header content block |
| [`DepertureController.php:527,683`](../app/Http/Controllers/Frontend/DepertureController.php#L527) | `group-tours` | 300s | Agent Connect API response (see below) |
| [`CountryController.php:120`](../app/Http/Controllers/Frontend/CountryController.php#L120) | `group-departures` | 300s | Same external API, country pages |
| [`CountryController.php:453`](../app/Http/Controllers/Frontend/CountryController.php#L453) | `group-tours` | 300s | Same external API |
| [`DestinationController.php:218`](../app/Http/Controllers/Frontend/DestinationController.php#L218) | (per-URL cache key) | 300s | Same external API, destination pages |
| [`RegionController.php:191`](../app/Http/Controllers/Frontend/RegionController.php#L191) | (per-URL cache key) | 300s | Same external API, region pages |
| [`BlogController.php:109`](../app/Http/Controllers/Frontend/BlogController.php#L109) | `blog_details_{slug}` | 60s | Response body from the external blog API (`blog.dookinternational.com/api/post_details`) |
| [`InquiryController.php:45,56`](../app/Http/Controllers/Frontend/InquiryController.php#L45) | `inquiry_data_{md5(ip+url)}` | 30 min | Geo/IP lookup result for an enquiry submission — uses raw `Cache::get`/`Cache::put` rather than `remember` |

Two patterns recur across these:

- **Caching an outbound HTTP call, not a database query.** The `group-departures` /
  `group-tours` keys (Country/Region/Destination/Departure controllers) and
  `blog_details_{slug}` (BlogController) all wrap a `curl`/`Http::` call to an external API
  (Agent Connect, and the separate WordPress-style blog API) in `Cache::remember`. This is
  as much about not hammering a third-party API on every page view as it is about
  application speed — a 300s TTL means at most one outbound call every 5 minutes per unique
  cache key, regardless of traffic.
- **`InquiryController` is the one place caching is used for something other than
  read-through query/API caching** — it memoizes a Geo/IP lookup result per
  `(IP, submitted URL)` pair for 30 minutes using explicit `Cache::get`/`Cache::put`, rather
  than `remember`, presumably because the "is it cached" check and the value construction
  are interleaved with other logic in that method.

None of these direct-controller call sites go through `cacheManySafe`/`cacheMarkUnavailable`
— they rely on Laravel's own `Cache::remember` internals, which do **not** have the
per-request circuit breaker described in §3.2. A Redis outage here means each of these
calls independently attempts (and times out on, per `REDIS_TIMEOUT=2.0s`) a connection
before whatever exception handling wraps it takes over — for the external-API ones, that's
usually a `try`/`catch Exception` around the whole block that logs and returns a 500 or
`null`, not a graceful per-key fallback like §3.2 provides.

---

## 5. What Redis is *not* used for

Worth stating plainly, since "is caching implemented" naturally raises the question of
scope:

- **Sessions**: [`config/session.php`](../config/session.php) defaults to
  `SESSION_DRIVER=database`; `docker/env.gcp.example` sets it to `file` explicitly (with a
  comment that `redis` would be needed only if this ever ran behind a load balancer across
  multiple instances — it currently runs as a single VM, so `file` is sufficient and avoids
  spending part of the 30-connection Redis budget on sessions).
- **Queues**: [`config/queue.php`](../config/queue.php) defaults to
  `QUEUE_CONNECTION=database`, unchanged in production. A `redis` queue connection exists
  in the config file (Laravel ships it by default) but nothing points at it.
- **HTTP/browser caching**: [`CacheHeaders.php`](../app/Http/Middleware/CacheHeaders.php)
  sets a `Cache-Control: public, max-age=600` response header on successful GET requests.
  This is a **completely separate mechanism** — it tells browsers/CDNs to cache the
  *rendered response*, and has nothing to do with Redis or the `Cache` facade. Easy to
  conflate by name; worth not doing so.
- **Framework object caches** (`config:cache`, `view:cache`, `route:cache` in
  `docker/entrypoint.sh`): these are Laravel build-time caches that serialize PHP config/
  view/route state to `bootstrap/cache/*.php` files on disk. Also unrelated to Redis.
  (`route:cache` is deliberately *not* run — see the comment block at the end of
  `docker/entrypoint.sh` and issue 11 in `redis_performance_report.md` for why.)
- **Redis `default` connection** (§2.2): configured, but currently unused by any code path.
- **Cache tags, locks, or `Cache::forget`**: a repo-wide search found no use of
  `Cache::tags()`, `Cache::lock()`, or explicit invalidation (`Cache::forget`/`flush`)
  anywhere. Every cache entry in this codebase expires **only via TTL** — nothing is ever
  explicitly evicted when the underlying data changes. This is why every TTL choice
  documented above (60s for admin-editable content, 21,600s for counts, 86,400s for
  near-static menu data) is effectively also the maximum staleness window after an edit.

---

## 6. Operational notes (from the deployment docs, for context)

These aren't code, but they explain constraints the code above is visibly written around:

- **Verifying Redis is reachable**: `gcp_hosting_guide.md` §6.4 gives the check —
  `php artisan tinker --execute="Cache::put('probe',1,10); var_dump(Cache::get('probe'));"`.
  `int(1)` means it's working; `NULL` means Redis is unreachable and every page is silently
  running on database fallback.
- **`maxmemory-policy` is `volatile-lru`** on the Redis Cloud plan in use, and `CONFIG SET`
  is blocked (can't be changed from the app side) — per
  `redis_performance_report.md`. Because every key this codebase writes carries a TTL (see
  §5's point about no explicit eviction — TTL is the *only* eviction mechanism here, which
  happens to line up with what `volatile-lru` needs to work at all: keys without a TTL are
  never evicted under that policy).
- **The 30-connection plan limit is the reason for**: `persistent` connections (§2.4),
  `pm.max_children = 10` with `pm = ondemand` (§2.4), and the single-VM (not autoscaled)
  deployment shape (§2.5).

---

## Appendix — every Redis/cache touchpoint, by file

```
config/cache.php                                    store definitions (redis → 'cache' connection)
config/database.php                                 redis connections: 'default' (unused) + 'cache'
config/session.php                                  NOT redis (database/file)
config/queue.php                                     NOT redis (database)
app/Http/Middleware/CacheHeaders.php                 NOT redis (HTTP Cache-Control header)
app/Http/Helper/Helper.php                           batching layer + fail-safe wrappers + 9 simple Cache::remember() helpers
app/Http/Controllers/Frontend/PoiController.php       calls poiDepartureCounts(), departureCardData()
app/Http/Controllers/Frontend/DestinationController.php  calls poiDepartureCountsUnfiltered(); Cache::remember() for Agent Connect API
app/Http/Controllers/Frontend/DepertureController.php     calls poiDepartureCounts(); 7 direct Cache::remember() call sites
app/Http/Controllers/Frontend/CountryController.php       2 direct Cache::remember() call sites (Agent Connect API)
app/Http/Controllers/Frontend/RegionController.php         1 direct Cache::remember() call site (Agent Connect API)
app/Http/Controllers/Frontend/BlogController.php           1 direct Cache::remember() call site (external blog API)
app/Http/Controllers/Frontend/InquiryController.php        Cache::get()/Cache::put() (Geo/IP memoization)
resources/views/frontend/layouts/topbar.blade.php    calls the 9 Helper.php Cache::remember() functions + 2 uncached ones
docker/www.conf, Dockerfile, docker-compose*.yml      infra sizing around the Redis connection budget
docker/env.gcp.example                               production Redis env vars (predis client, DB 0 only)
```
