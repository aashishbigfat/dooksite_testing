# Database Utilization Issue — High-Frequency `departures` COUNT Queries

## Problem

**Two** near-identical queries each called **700,000+ times** in 6 days (Cloud SQL Insights) against only ~35,000 site visitors — roughly 20x more DB calls than expected from real traffic. They are a matched pair, always fired together.

**Query 1 — featured departure count** (~715,086 calls, 5.47ms avg, ~7,816 rows scanned avg):
```sql
SELECT COUNT(DISTINCTROW `departures`.`id`) AS AGGREGATE
FROM `departure_destination_point_of_interests`
INNER JOIN `departures` ON `departures`.`id` = `departure_destination_point_of_interests`.`departure_id`
WHERE `departure_destination_point_of_interests`.`reference_id` = ?
  AND `departures`.`status` = ?
  AND `departures`.`featured` = ?
  AND `departures`.`dep_type` = ?
```

**Query 2 — total active departure count** (700,000+ calls):
```sql
SELECT COUNT(*) AS AGGREGATE
FROM `departure_destination_point_of_interests`
INNER JOIN `departures` ON `departures`.`id` = `departure_destination_point_of_interests`.`departure_id`
WHERE `departure_destination_point_of_interests`.`reference_id` = ?
  AND `departures`.`status` = ?
```

All load attributes to DB user `root` because that's the app's only DB connection user ([.env:28](../.env#L28)) — not a symptom of anything abnormal by itself.

## Root Cause: N+1 query loop

**File:** [app/Http/Controllers/Frontend/PoiController.php](../app/Http/Controllers/Frontend/PoiController.php)

Call chain on every `/poi/{slug}/{id}` page view:

1. `poi_details()` ([L47-48](../app/Http/Controllers/Frontend/PoiController.php#L47-L48)) calls `getRelatedPois()`.
2. `getRelatedPois()` ([L165-177](../app/Http/Controllers/Frontend/PoiController.php#L165-L177)) fetches **all** POI rows for the destination — no `limit()`.
3. `.map()` runs `processRelatedPoi()` on **every row**, and that method fires **2 COUNT queries per POI** — the two reported queries:
   - `total_departures` ([L181-189](../app/Http/Controllers/Frontend/PoiController.php#L181-L189)) — Query 2 (`COUNT(*)`, 2 params)
   - `featured_departure` ([L192-203](../app/Http/Controllers/Frontend/PoiController.php#L192-L203)) — Query 1 (`COUNT(DISTINCT departures.id)`, 4 params)
4. The Blade view (`poi_detail.blade.php` L93-95) then renders one card per related POI, each linking (`target="_blank"`) to *another* POI detail page — repeating the same loop.

**Result:** one page view = 2 × (number of related POIs in that destination) queries, uncapped. Multiplied across normal clicks and search-engine crawlers hitting these pages, this compounds into hundreds of thousands of calls.

### Contributing issues in the same code path

- **No caching** — counts are recomputed from scratch on every request, even though they rarely change.
- **`->distinct('destination_id')`** ([L171](../app/Http/Controllers/Frontend/PoiController.php#L171)) does not deduplicate by POI. Laravel's `distinct($col)` only toggles `SELECT DISTINCT` on the selected columns as a whole — it does not cap or group by POI, so the related-POI list size is unbounded.
- **Minor correctness gap in Query 2** — it uses plain `COUNT(*)` (no `DISTINCT`), so if a POI has duplicate link rows to the same departure in `departure_destination_point_of_interests`, `total_departures` would overcount. Worth spot-checking the "Tours" numbers shown on POI cards.
- **Unverifiable indexing** — `departures` and `departure_destination_point_of_interests` are not Laravel-migrated tables (no migration files exist for them), so index coverage on `reference_id` / `(status, featured, dep_type)` could not be confirmed from the codebase. ~7,816 avg rows scanned per call suggests weak/missing indexing.

## Resolution

Replace the per-POI query loop with **fixed-count grouped queries**, add caching, and cap the related list:

1. **Batch the counts** — fetch related POIs once, then compute counts for *all* of them in 2 grouped queries (`GROUP BY reference_id`) instead of 2 queries × N POIs. Reduces query count per page view from "2 × N" to a constant 3.
2. **Cap + dedupe the related POI list** — `groupBy('reference_id')` + `limit(8–12)` instead of the current uncapped, non-deduping `distinct('destination_id')`.
3. **Cache the counts** — wrap the grouped count queries in `Cache::remember(...)` (e.g. 6–24h TTL) since featured/active departure counts per POI change infrequently.
4. **Verify indexes** — run `SHOW INDEX FROM departure_destination_point_of_interests;` and `SHOW INDEX FROM departures;` on Cloud SQL; add indexes on `reference_id` and `(status, featured, dep_type)` if missing.

No code has been changed yet — this report is diagnostic only, pending sign-off to implement.
