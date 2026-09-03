# Why `/index.php/blog/...` URLs are appearing instead of `/blog/...`

## Symptom

Some blog pages are being served/indexed at URLs like:

```
https://www.dookinternational.com/index.php/blog/moynaq-city-once-happy-now-devastated-one-in-uzbekisatn/
```

instead of the correct:

```
https://www.dookinternational.com/blog/moynaq-city-once-happy-now-devastated-one-in-uzbekisatn/
```

This is a **web-server configuration gap in production**, not a routing bug or a
bad link generated in a Blade template. `routes/web.php` only defines
`/blog/{post_slug}/` ([routes/web.php:95](routes/web.php#L95)) — nothing in the
app ever builds an `/index.php/...` link on purpose, and the sitemap
([public/sitemap-blogs.xml:352](public/sitemap-blogs.xml#L352)) already lists the
clean URL. The bad URL is a *request variant* that the app is answering with
HTTP 200 instead of redirecting/rejecting, and then confirming as canonical.

## Root cause

**1. Production serves the app through nginx, which does not use `public/.htaccess`.**

[docker/nginx.conf](docker/nginx.conf) is the config actually running in
production (see `docker-compose.prod.yml`). Its own header comment says so
directly:

> "This intentionally does NOT read `public/.htaccess`... nginx never
> processes `.htaccess` files"

`public/.htaccess` *does* have a rule to strip `index.php` from incoming
requests (lines 144–147):

```apache
# Remove index.php
RewriteCond %{THE_REQUEST} \s/index\.php [NC]
RewriteRule ^index\.php$ / [R=301,L]
```

That rule is leftover from an Apache/cPanel deployment path and is now dead
code for the live site — nginx has no equivalent redirect. So a request to
`/index.php/blog/...` is never bounced to the clean `/blog/...` URL; it falls
straight through nginx's catch-all:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**2. Laravel/Symfony still successfully routes `/index.php/blog/...` requests (this is what makes it a silent 200, not a 404).**

When nginx hands a request like `/index.php/blog/xyz/` to PHP-FPM, `SCRIPT_NAME`
resolves to `/index.php` while `REQUEST_URI` (passed through verbatim from the
client) is still `/index.php/blog/xyz/`. Symfony's `Request` (which Laravel is
built on) detects that the URI starts with the script name and treats
`/index.php` as the "base URL," leaving `/blog/xyz/` as the effective path
info. The router matches `blogdetail` normally and renders the page — so the
malformed URL looks completely fine to a visitor or crawler; there's no error
and no redirect telling anyone it's wrong.

**3. The canonical tag is self-referencing, so it echoes the bad URL back instead of correcting it.**

[resources/views/frontend/layouts/header.blade.php:15](resources/views/frontend/layouts/header.blade.php#L15)
and [:17](resources/views/frontend/layouts/header.blade.php#L17) build the
canonical tag from `url()->current()`:

```blade
<link rel="canonical" href="{{ url()->current() }}/" />
...
<link rel="canonical" href="{{ url()->current() }}" />
```

`url()->current()` reconstructs the URL from the *current request's* base URL
+ path info — the same base-URL detection described in point 2. So when the
page is reached via `/index.php/blog/...`, the canonical tag itself says
`/index.php/blog/...` is canonical. That's very likely how the bad URL got
indexed by Google in the first place: nothing on the site currently *links*
to the `/index.php/...` form, but if it was ever crawled once (an old Apache-era
link, a stray backlink, a scraper, etc.), the page confirmed itself as
canonical and reinforced the wrong URL instead of pointing back to the clean
one.

*(Side note, not the cause of this bug but worth cleaning up alongside it:
the conditional above checks `$current_route_name == 'frontend.blogdetail'`
etc., but the actual route names in `routes/web.php` are unprefixed —
`blog`, `blogdetail`, `post_category_wise`
([routes/web.php:94-95](routes/web.php#L94-L95)) — with no `frontend.` route
group prefix anywhere in that file. So that trailing-slash branch never
actually matches on blog pages; both branches fall through to
`url()->current()` regardless, which is why the effect is the same either
way.)*

## Why this isn't a code/routing bug

- The route definition, controller, and sitemap all only ever reference the
  clean `/blog/{slug}/` form.
- No Blade view builds an href containing `index.php`.
- This is purely: **nothing in the production web-server config redirects
  away a legacy/malformed `/index.php/...` request**, and **the canonical tag
  trusts the current request URL instead of a fixed, generated one**.

## Fix direction (not applied — investigation only, per request)

Two independent, complementary fixes; either one alone reduces the problem,
both together close it:

1. **Add an nginx-level redirect** (in `docker/nginx.conf`) that 301-redirects
   any request whose URI starts with `/index.php/` to the same path with that
   prefix stripped — i.e. restore, at the nginx layer, the same intent as the
   dead `.htaccess` "Remove index.php" rule.
2. **Make the canonical tag not self-referencing.** Build it from the named
   route + known parameters (e.g. `route('blogdetail', $post_slug)`) rather
   than `url()->current()`, so the canonical tag is correct even if the page
   is ever reached through an odd/legacy URL variant.

Once fixed, request a re-crawl/removal of the `/index.php/...` URL in Google
Search Console so the old indexed entry drops out in favor of the clean one.
