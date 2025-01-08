<?php

use App\Models\Inclusion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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

if(!function_exists('getInclusionByName')):
    function getInclusionByName($names) {
       return  Cache::remember('Inclusion',86400, function () use ($names){
            return Inclusion::whereIn('name', $names)
            ->whereNotNull('icon')
            ->select('name', DB::raw('MAX(id) as id'), DB::raw('MAX(icon) as icon')) // Replace with actual columns and aggregation functions
            ->groupBy('name')
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


if(!function_exists('getBlog')):
    function getBlog() {
        return Http::get('https://blog.dookinternational.com/api/latest-post')->json()['posts'];
    }
endif;