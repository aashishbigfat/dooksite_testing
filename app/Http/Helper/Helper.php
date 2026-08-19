<?php

use App\Models\Inclusion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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