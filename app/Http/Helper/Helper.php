<?php

use App\Models\Inclusion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Storage\StorageClient;

function formatIndianNumber($number) {
    $number = (string)$number;
    $length = strlen($number);

    if ($length <= 3) {
        return $number; // No formatting needed for numbers with 3 or fewer digits
    }

    $lastThreeDigits = substr($number, -3);
    $remainingDigits = substr($number, 0, $length - 3);

    // Add commas every 2 digits in the remaining part
    $remainingDigits = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remainingDigits);

    return $remainingDigits . ',' . $lastThreeDigits;
}


function getFirstNonNullAttraction($data) {
    foreach ($data as $item) {
        if (!is_null($item['Attraction'])) {
            return $item['Attraction']; // Return the first item where 'Attraction' is not null
        }
    }
    return null; // Return null if no item with a non-null 'Attraction' is found
}

// Get Inclusion using name 

if (!function_exists('getInclusionByName')) :
    function getInclusionByName($names) {
        return Cache::remember('Inclusion_' . md5(json_encode($names)), 86400, function () use ($names) {
            return DB::table('inclusions as i')
                ->join('inclusion_masters as im', 'i.name', '=', 'im.name')
                ->whereIn('i.name', $names)
                ->whereNotNull('im.icon')
                ->select('i.name', 'im.icon')
                ->distinct()
                ->get();
        });
    }
endif;

// POI departure counts
//
// The POI cards ("N Tours / M Featured") used to run two COUNT queries per
// POI, inside a loop, on every page render - the single biggest source of
// query volume against Cloud SQL. These helpers batch the whole set into two
// grouped queries and cache the result per POI, so an attraction that appears
// on many pages is only ever counted once per TTL.
//
// Counts are cached per POI rather than per page: the same POI shows up on
// its own page, its destination's page and every package that visits it, so a
// per-POI key gets reused across all of them.

if (!function_exists('poiDepartureCountsTtl')):
    function poiDepartureCountsTtl() {
        return 21600; // 6 hours - counts only move when a departure is edited
    }
endif;

// The cache sits in the request path, so a Redis problem must never take a
// page down with it. If Redis is unreachable or refuses the connection (the
// plan's client limit being the likely cause), these degrade to a cache miss
// and the caller falls back to querying the database, exactly as it did
// before Redis existed - slower, but serving.
// Once Redis has failed in a request there is no point paying the connection
// timeout again for every later lookup in that same request - a page can call
// the count helpers more than once. The flag lives only for the current
// request, so the next one retries Redis normally.
if (!function_exists('cacheMarkUnavailable')):
    function cacheMarkUnavailable($mark = false) {
        static $unavailable = false;

        if ($mark) {
            $unavailable = true;
        }

        return $unavailable;
    }
endif;

if (!function_exists('cacheManySafe')):
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
endif;

if (!function_exists('cachePutManySafe')):
    function cachePutManySafe(array $values, $ttl) {
        if (!$values || cacheMarkUnavailable()) {
            return;
        }

        try {
            Cache::putMany($values, $ttl);
        } catch (\Throwable $e) {
            cacheMarkUnavailable(true);
            Log::warning('Cache write failed, values not cached: ' . $e->getMessage());
        }
    }
endif;

// Counts of ACTIVE departures (status = 1) per POI, deduplicated by departure.
// Used by the POI detail page and the package detail page.
if (!function_exists('poiDepartureCounts')):
    function poiDepartureCounts(array $poiIds) {
        $poiIds = array_values(array_unique(array_filter($poiIds, function ($id) {
            return $id !== null && $id !== '';
        })));

        if (!$poiIds) {
            return [];
        }

        $keyed = [];
        foreach ($poiIds as $poiId) {
            $keyed['poi_dep_counts_' . $poiId] = $poiId;
        }

        $cached = cacheManySafe(array_keys($keyed));

        $counts = [];
        $missing = [];
        foreach ($keyed as $cacheKey => $poiId) {
            if (is_array($cached[$cacheKey] ?? null)) {
                $counts[$poiId] = $cached[$cacheKey];
            } else {
                $missing[] = $poiId;
            }
        }

        if ($missing) {
            $totals = DB::table('departure_destination_point_of_interests as dpi')
                ->join('departures as d', 'd.id', '=', 'dpi.departure_id')
                ->whereIn('dpi.reference_id', $missing)
                ->where('d.status', 1)
                ->groupBy('dpi.reference_id')
                ->selectRaw('dpi.reference_id as ref, COUNT(DISTINCT d.id) as cnt')
                ->pluck('cnt', 'ref')
                ->all();

            $featured = DB::table('departure_destination_point_of_interests as dpi')
                ->join('departures as d', 'd.id', '=', 'dpi.departure_id')
                ->whereIn('dpi.reference_id', $missing)
                ->where('d.status', 1)
                ->where('d.featured', 1)
                ->where('d.dep_type', 'package')
                ->groupBy('dpi.reference_id')
                ->selectRaw('dpi.reference_id as ref, COUNT(DISTINCT d.id) as cnt')
                ->pluck('cnt', 'ref')
                ->all();

            $fresh = [];
            foreach ($missing as $poiId) {
                $row = [
                    'total'    => (int) ($totals[$poiId] ?? 0),
                    'featured' => (int) ($featured[$poiId] ?? 0),
                ];
                $fresh['poi_dep_counts_' . $poiId] = $row;
                $counts[$poiId] = $row;
            }

            cachePutManySafe($fresh, poiDepartureCountsTtl());
        }

        return $counts;
    }
endif;

// Counts of ALL linked departures per POI, regardless of status, counting
// duplicate link rows. Deliberately different from poiDepartureCounts() above
// because the destination page has always reported these looser numbers -
// this only batches and caches them, it does not change what they mean.
if (!function_exists('poiDepartureCountsUnfiltered')):
    function poiDepartureCountsUnfiltered(array $poiIds) {
        $poiIds = array_values(array_unique(array_filter($poiIds, function ($id) {
            return $id !== null && $id !== '';
        })));

        if (!$poiIds) {
            return [];
        }

        $keyed = [];
        foreach ($poiIds as $poiId) {
            $keyed['poi_dep_counts_all_' . $poiId] = $poiId;
        }

        $cached = cacheManySafe(array_keys($keyed));

        $counts = [];
        $missing = [];
        foreach ($keyed as $cacheKey => $poiId) {
            if (is_array($cached[$cacheKey] ?? null)) {
                $counts[$poiId] = $cached[$cacheKey];
            } else {
                $missing[] = $poiId;
            }
        }

        if ($missing) {
            $totals = DB::table('departure_destination_point_of_interests')
                ->whereIn('reference_id', $missing)
                ->groupBy('reference_id')
                ->selectRaw('reference_id as ref, COUNT(*) as cnt')
                ->pluck('cnt', 'ref')
                ->all();

            $featured = DB::table('departure_destination_point_of_interests as dpi')
                ->join('departures as d', 'd.id', '=', 'dpi.departure_id')
                ->whereIn('dpi.reference_id', $missing)
                ->where('d.featured', 1)
                ->groupBy('dpi.reference_id')
                ->selectRaw('dpi.reference_id as ref, COUNT(*) as cnt')
                ->pluck('cnt', 'ref')
                ->all();

            $fresh = [];
            foreach ($missing as $poiId) {
                $row = [
                    'total'    => (int) ($totals[$poiId] ?? 0),
                    'featured' => (int) ($featured[$poiId] ?? 0),
                ];
                $fresh['poi_dep_counts_all_' . $poiId] = $row;
                $counts[$poiId] = $row;
            }

            cachePutManySafe($fresh, poiDepartureCountsTtl());
        }

        return $counts;
    }
endif;

// Departure card data
//
// Filling in one tour-package card used to take six queries - attraction
// names, lead price, inclusion icons, and a three-step departure ->
// destination -> country walk - all run inside the loop over the six
// paginated departures, so 36 queries per page view. None of the columns
// involved are indexed either, so the inclusions lookup alone read all 30,000
// rows of that table once per departure.
//
// These fetch the whole page's worth in four batched queries and cache the
// result per departure, so the same package appearing on several pages is
// only assembled once per TTL.
//
// Nothing here stores a signed URL: those expire, so raw storage paths are
// cached and the caller signs them at render time.

if (!function_exists('departureCardTtl')):
    function departureCardTtl() {
        // Matches the TTL the existing departure_{id}_poi and
        // departure_{id}_inclusions caches already use, so an edit made in the
        // admin shows up on the cards just as quickly as it did before.
        return 60;
    }
endif;

if (!function_exists('departureCardData')):
    function departureCardData(array $departureIds) {
        $departureIds = array_values(array_unique(array_filter($departureIds, function ($id) {
            return $id !== null && $id !== '';
        })));

        if (!$departureIds) {
            return [];
        }

        $keyed = [];
        foreach ($departureIds as $departureId) {
            $keyed['dep_card_' . $departureId] = $departureId;
        }

        $cached = cacheManySafe(array_keys($keyed));

        $cards = [];
        $missing = [];
        foreach ($keyed as $cacheKey => $departureId) {
            if (is_array($cached[$cacheKey] ?? null)) {
                $cards[$departureId] = $cached[$cacheKey];
            } else {
                $missing[] = $departureId;
            }
        }

        if ($missing) {
            $poiNames   = departureCardPoiNames($missing);
            $prices     = departureCardPrices($missing);
            $inclusions = departureCardInclusions($missing);
            $countries  = departureCardCountries($missing);

            $fresh = [];
            foreach ($missing as $departureId) {
                $row = [
                    'poi_names'    => $poiNames[$departureId] ?? [],
                    // null when the departure has no hotel category at all,
                    // which is what tells the caller to leave the price alone.
                    'price'        => $prices[$departureId] ?? null,
                    'inclusions'   => $inclusions[$departureId] ?? [],
                    'country_name' => $countries[$departureId] ?? null,
                ];
                $fresh['dep_card_' . $departureId] = $row;
                $cards[$departureId] = $row;
            }

            cachePutManySafe($fresh, departureCardTtl());
        }

        return $cards;
    }
endif;

// Up to four attraction names per departure, as the card lists them.
if (!function_exists('departureCardPoiNames')):
    function departureCardPoiNames(array $departureIds) {
        $rows = DB::table('departure_destination_point_of_interests')
            ->whereIn('departure_id', $departureIds)
            ->where('status', 1)
            ->select('departure_id', 'poi_name')
            ->distinct()
            ->get();

        $names = [];
        foreach ($rows as $row) {
            if (count($names[$row->departure_id] ?? []) < 4) {
                $names[$row->departure_id][] = $row->poi_name;
            }
        }

        return $names;
    }
endif;

// Cheapest hotel category per departure - the "from" price on the card.
if (!function_exists('departureCardPrices')):
    function departureCardPrices(array $departureIds) {
        $rows = DB::table('hotel_categories')
            ->whereIn('departure_id', $departureIds)
            ->orderBy('price_inr', 'ASC')
            ->select('departure_id', 'price_inr', 'price_usd')
            ->get();

        $prices = [];
        foreach ($rows as $row) {
            // Ordered by price, so the first row seen for a departure is its
            // cheapest - the same one ->orderBy(...)->first() returned.
            if (!isset($prices[$row->departure_id])) {
                $prices[$row->departure_id] = [
                    'inr' => $row->price_inr,
                    'usd' => $row->price_usd,
                ];
            }
        }

        return $prices;
    }
endif;

// Inclusion icons per departure, as raw storage paths.
if (!function_exists('departureCardInclusions')):
    function departureCardInclusions(array $departureIds) {
        $rows = DB::table('inclusions')
            ->join('icon_inclusions', 'inclusions.icon_inclusion_id', '=', 'icon_inclusions.id')
            ->whereIn('inclusions.departure_id', $departureIds)
            ->whereNotNull('icon_inclusions.icon_old')
            ->select('inclusions.departure_id', 'icon_inclusions.name', 'icon_inclusions.icon')
            ->get();

        $inclusions = [];
        foreach ($rows as $row) {
            $inclusions[$row->departure_id][] = [
                'name' => $row->name,
                'icon' => $row->icon,
            ];
        }

        return $inclusions;
    }
endif;

// departure -> destination -> country, batched.
//
// Deliberately three lookups rather than one join: the per-departure version
// took the first departure_destinations row with value() and followed only
// that one, reporting no country when that destination had none. A join would
// instead skip ahead to whichever destination does have a country, which is a
// different answer.
if (!function_exists('departureCardCountries')):
    function departureCardCountries(array $departureIds) {
        $destinationByDeparture = [];
        $links = DB::table('departure_destinations')
            ->whereIn('departure_id', $departureIds)
            ->select('departure_id', 'destination_id')
            ->get();

        foreach ($links as $link) {
            if (!isset($destinationByDeparture[$link->departure_id])) {
                $destinationByDeparture[$link->departure_id] = $link->destination_id;
            }
        }

        if (!$destinationByDeparture) {
            return [];
        }

        $countryByDestination = DB::table('destinations')
            ->whereIn('id', array_unique(array_values($destinationByDeparture)))
            ->pluck('country_id', 'id')
            ->all();

        $countryIds = array_filter(array_unique(array_values($countryByDestination)));

        $nameByCountry = $countryIds
            ? DB::table('countries')->whereIn('id', $countryIds)->pluck('country_name', 'id')->all()
            : [];

        $countries = [];
        foreach ($destinationByDeparture as $departureId => $destinationId) {
            $countryId = $countryByDestination[$destinationId] ?? null;
            $countries[$departureId] = $countryId ? ($nameByCountry[$countryId] ?: null) : null;
        }

        return $countries;
    }
endif;

// Get Best Selleng Package

if (!function_exists('getBestSellingPackage')):
    function getBestSellingPackage($data) {
        $filtered = array_filter($data, function ($item) {
            return isset($item['BestSellingPackage']) && $item['BestSellingPackage'] === true;
        });

        // Convert the filtered result to an indexed array to shuffle properly
       $filtered = array_values($filtered);
       // Shuffle the array
       shuffle($filtered);
       // Return only the first 6 results
       return array_slice($filtered, 0, 6);
    }
       
endif;


//

// Function to get Mega Menu Countries
if (!function_exists('getMegaMenuCountries')) {
    function getMegaMenuCountries() {
        return Cache::remember('mega_menu_countries', 86400, function () {
            return DB::table('mega_menu_countries')
                ->select('mega_menu_countries.country_id as id', 'mega_menu_countries.name', 'countries.slug_url', 'countries.flag')
                ->leftJoin('countries', 'mega_menu_countries.country_id', '=', 'countries.id')
                ->orderBy('mega_menu_countries.order', 'ASC')
                ->limit(16)
                ->get();
        });
    }
}

// Function to get Mega Menu Destinations
if (!function_exists('getMegaMenuDestinations')) {
    function getMegaMenuDestinations() {
        return Cache::remember('mega_menu_destinations', 86400, function () {
            return DB::table('mega_menu_destinations')
                ->select('mega_menu_destinations.destination_id as id', 'mega_menu_destinations.name', 'destinations.slug_url')
                ->leftJoin('destinations', 'mega_menu_destinations.destination_id', '=', 'destinations.id')
                ->orderBy('mega_menu_destinations.order', 'ASC')
                ->limit(16)
                ->get();
        });
    }
}

// Function to get Mega Regions
if (!function_exists('getMegaRegions')) {
    function getMegaRegions() {
        return Cache::remember('mega_regions', 86400, function () {
            return DB::table('regions')
                ->where('mega_menu', 1)
                ->select('region_name', 'slug_url')
                ->orderBy('grid_number', 'ASC')
                ->limit(15)
                ->get();
        });
    }
}

// Function to get Mega Experience
if (!function_exists('getMegaExperience')) {
    function getMegaExperience() {
        // return Cache::remember('mega_experience', 86400, function () {
            return DB::table('experiences')
                ->where('status', 1)
                ->where('exp_status', 1)
                ->where('show_at_home',1)
                ->select('experience_name', 'slug_url')
                ->orderBy('sorting', 'ASC')
                ->get();
        // });
    }
}

if (!function_exists('getMegaeServices')) {
    function getMegaeServices() {
        // return Cache::remember('experiences', 86400, function () {
            return DB::table('experiences')
                ->where('status', 1)
                ->where('service_status', 1)
                ->where('show_at_home',1)
                ->select('experience_name', 'slug_url')
                ->orderBy('sorting', 'ASC')
                ->get();
        // });
    }
} 

if (!function_exists('countries')) {
    function countries() {
        return Cache::remember('countries', 86400, function () {
            return DB::table('footer_countries')
        ->join('countries','countries.id','=','footer_countries.country_id')
        ->select('countries.country_name as name','countries.slug_url as slug')
        ->orderBy('footer_countries.orders','ASC')
        ->get();
        });
    }
} 

if (!function_exists('destinations')) {
    function destinations() {
        return Cache::remember('destinations', 86400, function () {
            return DB::table('footer_destinations')
        ->join('destinations','destinations.id','=','footer_destinations.destination_id')
        ->select('destinations.dest_name as name','destinations.slug_url as slug')
        ->orderBy('footer_destinations.orders','ASC')
        ->get(); 
    });
} 
}

if (!function_exists('experiences')) {
    function experiences() {
        return Cache::remember('experiences', 86400, function () {
            return DB::table('experiences')
        ->where('status',1)
        ->where('slug_url','!=','')
        ->select('experience_name as name','slug_url as slug')
        ->get()->toArray();
    });
} 
}

if (!function_exists('generateSignedUrl')) {
    function generateSignedUrl($imagePath)
    {
        // Ensure the service account credentials are set
        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . env('GOOGLE_CLOUD_KEY_FILE'));
        // \Log::info("Google Cloud Key File Path: " . env('GOOGLE_CLOUD_KEY_FILE'));

        // Create the Google Cloud Storage client
        $storage = new StorageClient();

        // Retrieve the bucket name from the environment
        $bucketName = env('GOOGLE_CLOUD_STORAGE_BUCKET');
        $bucket = $storage->bucket($bucketName);

        // Access the object (image) from the bucket
        $object = $bucket->object($imagePath);

        try {
            // Generate the signed URL valid for 24 hours
            $signedUrl = $object->signedUrl(
                new \DateTime('tomorrow'),  // URL valid for 24 hours
                ['version' => 'v4']  // Use v4 signed URL version
            );
            return $signedUrl;
        } catch (\Exception $e) {
            // \Log::error("Error generating signed URL: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('mega_regions_europe')) {
    function mega_regions_europe() {
        return Cache::remember('mega_regions_europe', 86400, function () {
            return DB::table('regions')
         ->where('region_name', 'Europe')
                ->select('region_name', 'slug_url')
                ->first();
    });
} 
}
if (!function_exists('mega_regions_africa')) {
    function mega_regions_africa() {
        return Cache::remember('mega_regions_africa', 86400, function () {
            return DB::table('regions')
         ->where('region_name', 'Africa')
                ->select('region_name', 'slug_url')
                ->first();
    });
} 
}