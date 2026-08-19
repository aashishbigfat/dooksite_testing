<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingDeparturePage;
use App\Models\DepartureDestinationPointOfInterest;
use App\Models\Region;
use App\Models\Country;
use App\Models\Inclusion;
use App\Models\Departure;
use App\Models\Experience;
use App\Models\Destination;
use App\Models\DepartureDestination;
use App\Models\CountryDeparture;
use App\Models\IconInclusion;
use App\Models\AllCountry;
use App\Models\RegionCountry;
use Illuminate\Support\Facades\Log;
use App\Models\RegionExperience;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use DB;

class RegionController extends Controller
{
    public function regions(Request $request)
    {
        $region_header = LandingDeparturePage::where('type', 'landing_region')
                                              ->select('title', 'sub_title', 'description', 'meta_title', 'meta_keywords', 'meta_description', 'description')
                                              ->first();
        $regions = Region::select('id', 'grid_number', 'region_name', 'label_name', 'slug_url', 'image')
                         ->orderBy('grid_number', 'ASC')
                         ->limit(26)
                         ->get();
        if(count($regions)>0){
            foreach ($regions as $key => $country_value) {
                $region_country = RegionCountry::where('region_id',$country_value->id)
                        ->pluck('country_id')
                        ->toArray();
                $region_country_array = array();
                foreach ($region_country as $key => $value) {
                    $countries = Country::where('id', $value)
                        ->where('status',1)
                        ->select('id','country_name','slug_url')
                        ->first();
                    if ($countries) {
                        array_push($region_country_array, $countries);
                    }
                }

                $country_value->countries = $region_country_array;
                $country_value->image = generateSignedUrl('region/'.$country_value->image);
            }

            //Experiences
            foreach ($regions as $key => $exp_value) {
                $region_country = RegionExperience::where('region_id',$exp_value->id)
                        ->pluck('experience_id')
                        ->toArray();
                $region_exp_array = array();
                foreach ($region_country as $key => $value) {
                    $experience_row = Experience::where('id', $value)
                        ->select('id','experience_name','slug_url')
                        ->first();
                    if($experience_row){
                        array_push($region_exp_array, $experience_row);
                    }
                }

                $exp_value->experiences = $region_exp_array;
            }
        }
        $regionId = $request->keyword ?? $regions->first()->id;
        $departures = Departure::join('country_departure_destination_regions', 'country_departure_destination_regions.departure_id', '=', 'departures.id')
                               ->where('country_departure_destination_regions.region_id', $regionId)
                               ->where('departures.status', 1)
                               ->where('departures.dep_type', 'package')
                               ->select('departures.id', 'departures.title', 'departures.price_currency', 'departures.price', 'departures.price_currency_usd', 
                                        'departures.price_usd', 'departures.book_online', 'departures.price_hide_show', 'departures.no_of_days', 
                                        'departures.no_of_nights', 'departures.slug_url_pre as slug1', 'departures.slug_url as slug2', 
                                        'departures.dep_dook_ref_id as slug3', 'departures.image', 'departures.created_at', 'departures.featured')
                               ->distinct('departures.created_at')
                               ->orderBy('departures.featured', 'DESC')
                               ->paginate(8);
        foreach ($departures as $departure) {
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
            $departure->poi_names = $poiNames;
            $departure->title = ucwords(strtolower($departure->title));
            $departure->dimage = $departure->image;
            $departure->image = $departure->image ? generateSignedUrl('package/'.$departure->image) : url('images').'/package-no-image.jpg';
            $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';
             $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";

            $inclusions = DB::table('inclusions')
                    ->join('inclusion_masters', 'inclusions.icon_inclusion_id', '=', 'inclusion_masters.id')
                    ->where('inclusions.departure_id', $departure->id)
                    ->whereNotNull('inclusions.icon_inclusion_id')
                    ->select('inclusion_masters.name', 'inclusion_masters.icon')
                    ->distinct()
                    ->get();

            foreach ($inclusions as $inclusion) {
                $inclusion->icon = generateSignedUrl('inclusion/'.$inclusion->icon);
            }

            $departure->inclusions = $inclusions;
            $destinationId = DB::table('departure_destinations')
                            ->where('departure_id', $departure->id)
                            ->value('destination_id');

                if ($destinationId) {
                    $countryId = DB::table('destinations')
                        ->where('id', $destinationId)
                        ->value('country_id');

                    if ($countryId) {
                        $countryName = DB::table('countries')
                            ->where('id', $countryId)
                            ->value('country_name');

                        $departure->country_name = $countryName ?: null;
                    } else {
                        $departure->country_name = null;
                    }
                } else {
                    $departure->country_name = null;
                }   
        }
        if ($request->ajax()) {
            return response()->json([
                'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                'hasMorePages' => $departures->hasMorePages()
            ]);
        }

        $region_picklist = Region::select('id', 'grid_number', 'region_name', 'label_name', 'slug_url', 'image')
                                 ->orderBy('grid_number', 'ASC')
                                 ->get();

        return view('frontend.regions.region', compact('region_header', 'regions', 'departures', 'region_picklist'));
    }
    public function regionDetails(Request $request, $slug)
    {
        $region_destination_name = [];
        $region = Region::where('slug_url',$slug)
            ->select('id','region_name','label_name as title','sub_title','slug_url','banner_image','description','meta_title','meta_keywords','meta_description')
            ->first(); 
        if(!$region || $region->slug_url !== $slug){
            //return view('errors.');
          return redirect('/');
        } 
        
        $commoninquiryregionid = [$region->id];

        $common_inquiry_region_name = Destination::whereIn('region_id', $commoninquiryregionid)
            ->distinct()
            ->pluck('dest_name')
            ->toArray();

       
        $regions = Region::select('id','grid_number','region_name','label_name','slug_url','image')
            ->orderBy('grid_number','ASC')
            ->limit(26)
            ->get();

        if(!is_null($request->keyword)){
            $regionId = $request->keyword;
        }else{
            $regionId = $region->id;
        }       
       $username = env('AGENT_CONNECT_USERNAME');
        $password = env('AGENT_CONNECT_PASSWORD');

        // Set headers for authentication
        $headerArray = [
            'Username: ' . $username,
            'Password: ' . $password,
        ];

        // API base URL and endpoint
        $baseUrl = 'https://agent.dookinternational.com/api';
        $url = $baseUrl . '/departure/group-departure';

        // Set the cache key
        $cacheKey = 'group-departures';

        try {
            // Try to get the cached response
            $departures = Cache::remember($cacheKey, 300, function () use ($url, $headerArray) {
                // Initialize cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // Set the HTTP method to GET
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray); // Set the headers

                // Execute the cURL request
                $result = curl_exec($ch);

                // Check if there was an error with the request
                if (curl_errno($ch)) {
                    // If there's an error, log it and return null
                    Log::error('cURL Error: ' . curl_error($ch));
                    curl_close($ch);
                    return null;
                }

                // Close cURL session
                curl_close($ch);

                // Decode the JSON response into an associative array
                $data1 = json_decode($result);

                // Extract the 'Result' from the API response
                if (isset($data1->Result)) {
                    return $data1->Result;
                } else {
                    // Handle the case where 'Result' is not found in the response
                    Log::error('API response structure has changed or no result found.');
                    return null;
                }
            });


        } catch (Exception $e) {
            // Log and return error if an exception occurs
            Log::error("API Error", ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve data'], 500);
        } 
        if($slug == 'europe-tour-packages'){

           $region_ids = [22,28,14];
           $common_inquiry_region_name = Destination::whereIn('region_id', $region_ids) 
            ->distinct()
            ->pluck('dest_name')
            ->toArray();
            $data_country = Country::all();
            $country_id = [];
            foreach ($data_country as $key => $value) {
                $region_arr = explode(',', $value->region_id);
                foreach ($region_arr as $region_value) {
                    if(in_array($region_value, $region_ids)){
                        array_push($country_id, $value->id);
                    }
                }
            }       
            if(count($country_id)>0){  
                $departure_ids = CountryDeparture::whereIn('country_id',$country_id)->pluck('departure_id')->toArray();

                // added by anirudh    
                $all_destinaton_id = DepartureDestination::whereIn('departure_id',$departure_ids)
                    ->distinct()
                    ->pluck('destination_id')
                    ->toArray();

                $region_destination_name = Destination::whereIn('id',$all_destinaton_id)
                    ->pluck('dest_name')
                    ->sort()
                    ->toArray();
                // end by anirudh  
                 $departuresFromDB = Departure::whereIn('id', $departure_ids)
                        ->where('status', 1)
                        ->select('id','title','price_currency','price','price_currency_usd','price_usd','book_online','price_hide_show','no_of_days','no_of_nights','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','image','created_at','featured','dep_type')
                        ->distinct('created_at')
                        ->orderBy('featured','DESC')
                         ->where('dep_type', 'package')
                        ->get();
                // agent departure
                    
                if ($departures && isset($departures)) {
                    $depar = $departures;
                    $matchingDepartures = [];
                    $iconInclusions = IconInclusion::all()->keyBy('name');
                    foreach ($depar as $departure) {    
                            if (!empty($departure->Slug)) {
                                $departure->Name = strtok($departure->Name, '-');
                                $regionName = $region->region_name;
                                $normalizedInputName = strtolower(str_replace(' ', '', $regionName));
                                $regionId = Country::where('country_name', $departure->Country)
                                    ->value('region_id');

                                       if (in_array($regionId, $region_ids)) {
                                         $poi_names = [];

                                        if (!empty($departure->Itinerary)) {
                                            $departure->Itinerary = array_map(function ($item) use (&$poi_names) {
                                                $poi = $item->Attraction ?? [];
                                                $poi_names = array_merge($poi_names, $poi);
                                                return (object) [
                                                    'poi' => $poi,
                                                ];
                                            }, $departure->Itinerary);
                                        }

                                        $departure->poi = array_map(function ($poiItem) {
                                            return $poiItem->Name;
                                        }, array_slice(array_unique($poi_names, SORT_REGULAR), 0, 4)); 

                                      $inclusions = $departure->DepartureDateWithPrice[0]->Inclusion ?? [];
                                       
                                        $mappedInclusions = array_map(function ($offer) use ($iconInclusions) {
                                            $icon = isset($iconInclusions[$offer]) ? env('AWS_BUCKET_URL') . "/inclusion/" . $iconInclusions[$offer]->icon : null;
                                            return (object) [
                                                'name' => $offer,
                                                'icon' => $icon,
                                            ];
                                        }, $inclusions);

                                        $matchingDeparture = new \stdClass();
                                        $matchingDeparture->slug1 = 'group-tours'; 
                                        $matchingDeparture->slug2 = $departure->DookSlug; 
                                        $matchingDeparture->slug3 = $departure->DookDepartureId;
                                        $matchingDeparture->image = $departure->DookImage[0] ?? asset('assets/images/maine-Image.jpg'); 
                                        $matchingDeparture->title = strtok($departure->Name, '-');
                                        $matchingDeparture->featured = $departure->BestSellingPackage;
                                        $matchingDeparture->price = $departure->MinimumPublishedPrice ?? $departure->Price ?? null;
                                        $matchingDeparture->no_of_nights = $departure->DayNight;
                                        $matchingDeparture->poi_names = $departure->poi;
                                        $matchingDeparture->inclusions = $mappedInclusions;
                                        $matchingDeparture->destinations = $departure->Destination;
                                        $matchingDeparture->colMd = 'col-md-4 col-12';
                                        $matchingDeparture->offers = $departure->Offers;
                                        $matchingDeparture->country_name = is_array($departure->Country) ? $departure->Country[0] : $departure->Country;

                                        $matchingDepartures[] = $matchingDeparture;
                                    }
                            }
                    }
                } 
                foreach ($departuresFromDB as $departure) {
                    $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                    $departure->poi_names = $poiNames;
                    $departure->title = ucwords(strtolower($departure->title));
                    $departure->dimage = $departure->image;
                    $departure->image = $departure->image ? generateSignedUrl('package/'.$departure->image) : url('images').'/package-no-image.jpg';
                     $departure->featured = $departure->featured ? 'Best Selling' : '';
                    $departure->colMd = "col-md-4 col-12";
                      $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
             

                   $inclusions = DB::table('inclusions')
                    ->join('inclusion_masters', 'inclusions.icon_inclusion_id', '=', 'inclusion_masters.id')
                    ->where('inclusions.departure_id', $departure->id)
                    ->whereNotNull('inclusions.icon_inclusion_id')
                    ->select('inclusion_masters.name', 'inclusion_masters.icon')
                    ->distinct()
                    ->get();



                    foreach ($inclusions as $inclusion) {
                        $inclusion->icon = generateSignedUrl('inclusion/'.$inclusion->icon);
                    }

                    $departure->inclusions = $inclusions;
                    $destinationId = DB::table('departure_destinations')
                            ->where('departure_id', $departure->id)
                            ->value('destination_id');

                if ($destinationId) {
                    $countryId = DB::table('destinations')
                        ->where('id', $destinationId)
                        ->value('country_id');

                    if ($countryId) {
                        $countryName = DB::table('countries')
                            ->where('id', $countryId)
                            ->value('country_name');

                        $departure->country_name = $countryName ?: null;
                    } else {
                        $departure->country_name = null;
                    }
                } else {
                    $departure->country_name = null;
                }   
                    }

                    $departuresFromDB = json_decode(json_encode($departuresFromDB->toArray()));
                    $departures = array_merge($matchingDepartures,$departuresFromDB);
                    foreach ($departures as $departure) {   
                        if (isset($departure->featured) && $departure->featured == 'Best Selling') {
                            $departure->featured = 1; 
                        } elseif (isset($departure->featured) && $departure->featured != 'Best Selling') {
                            $departure->featured = 0; 
                        }

                        if (isset($departure->BestSellingPackage) && $departure->BestSellingPackage == 1) {
                            $departure->featured = 1; 
                        } elseif (isset($departure->BestSellingPackage) && $departure->BestSellingPackage != 1) {
                            $departure->featured = 0;
                        }
                    }
                    usort($departures, function ($a, $b) {
                        if (is_object($a) && is_object($b)) {
                            if ($a->featured == 1 && $b->featured != 1) {
                                return -1;
                            }
                            if ($a->featured != 1 && $b->featured == 1) {
                                return 1; 
                            }
                        }
                        return 0; 
                    });

                    $currentPage = $request->get('page', 1);
                    $perPage = 6;
                    $totalItems = count($departures);
                    $currentItems = array_slice($departures, ($currentPage - 1) * $perPage, $perPage);
                    $departures = new \Illuminate\Pagination\LengthAwarePaginator(
                        $currentItems,
                        $totalItems,
                        $perPage,
                        $currentPage,
                       ['path' => $request->url(), 'query' => $request->query()]
                    );
                   
                $countries = Country::whereIn('id',$country_id)
                    ->where('status',1)
                    ->where('slug_url','!=','')
                    ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                    ->orderBy('country_exist', 'DESC')
                    ->paginate(8);
                foreach ($countries as $key => $value) {
                    if($value->image != "" || $value->image != null){
                    $value->image = generateSignedUrl('country/'.$value->image);
                    }else{
                        $value->image = url('images').'/poi-no-image.jpg';
                    }
                }
            }
        }
        elseif($slug == 'africa-tour-packages'){
            $region_ids = [3,7,19,12];
             $common_inquiry_region_name = Destination::whereIn('region_id', $region_ids) // Use $region_ids directly
            ->distinct()
            ->pluck('dest_name')
            ->toArray();
            $data_country = Country::all();
            $country_id = [];
            foreach ($data_country as $key => $value) {
                $region_arr = explode(',', $value->region_id);
                foreach ($region_arr as $region_value) {
                    if(in_array($region_value, $region_ids)){
                        array_push($country_id, $value->id);
                    }
                }
            }       
             if(count($country_id)>0){  
                $departure_ids = CountryDeparture::whereIn('country_id',$country_id)->pluck('departure_id')->toArray();

                // added by anirudh    
                $all_destinaton_id = DepartureDestination::whereIn('departure_id',$departure_ids)
                    ->distinct()
                    ->pluck('destination_id')
                    ->toArray();

                $region_destination_name = Destination::whereIn('id',$all_destinaton_id)
                    ->pluck('dest_name')
                    ->sort()
                    ->toArray();
                // end by anirudh  
                 $departuresFromDB = Departure::whereIn('id', $departure_ids)
                        ->where('status', 1)
                        ->select('id','title','price_currency','price','price_currency_usd','price_usd','book_online','price_hide_show','no_of_days','no_of_nights','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','image','created_at','featured','dep_type')
                        ->distinct('created_at')
                        ->orderBy('featured','DESC')
                         ->where('dep_type', 'package')
                        ->get();
                // agent departure
                    
               if ($departures && isset($departures)) {
                    $depar = $departures;
                    $matchingDepartures = [];
                    $iconInclusions = IconInclusion::all()->keyBy('name');
                    foreach ($depar as $departure) {    
                            if (!empty($departure->Slug)) {
                                $departure->Name = strtok($departure->Name, '-');
                                $regionName = $region->region_name;
                                $normalizedInputName = strtolower(str_replace(' ', '', $regionName));
                                $regionId = AllCountry::where('country_name', $departure->Country)
                                    ->value('region_id');

                                       if (in_array($regionId, $region_ids)) {
                                         $poi_names = [];

                                        if (!empty($departure->Itinerary)) {
                                            $departure->Itinerary = array_map(function ($item) use (&$poi_names) {
                                                $poi = $item->Attraction ?? [];
                                                $poi_names = array_merge($poi_names, $poi);
                                                return (object) [
                                                    'poi' => $poi,
                                                ];
                                            }, $departure->Itinerary);
                                        }

                                        $departure->poi = array_map(function ($poiItem) {
                                            return $poiItem->Name;
                                        }, array_slice(array_unique($poi_names, SORT_REGULAR), 0, 4)); 

                                        $inclusions = $departure->DepartureDateWithPrice[0]->Inclusion ?? [];                                       
                                        $mappedInclusions = array_map(function ($offer) use ($iconInclusions) {
                                            $icon = isset($iconInclusions[$offer]) ? generateSignedUrl('inclusion/' . $iconInclusions[$offer]->icon) : null;
                                            return (object) [
                                                'name' => $offer,
                                                'icon' => $icon,
                                            ];
                                        }, $inclusions);

                                        $matchingDeparture = new \stdClass();
                                        $matchingDeparture->slug1 = 'group-tours'; 
                                        $matchingDeparture->slug2 = $departure->DookSlug; 
                                        $matchingDeparture->slug3 = $departure->DookDepartureId;
                                        $matchingDeparture->image = $departure->DookImage[0] ?? asset('assets/images/maine-Image.jpg'); 
                                        $matchingDeparture->title = strtok($departure->Name, '-');
                                        $matchingDeparture->featured = $departure->BestSellingPackage;
                                        $matchingDeparture->price = $departure->MinimumPublishedPrice ?? $departure->Price ?? null;
                                        $matchingDeparture->no_of_nights = $departure->DayNight;
                                        $matchingDeparture->poi_names = $departure->poi;
                                        $matchingDeparture->inclusions = $mappedInclusions;
                                        $matchingDeparture->destinations = $departure->Destination;
                                        $matchingDeparture->colMd = 'col-md-4 col-12';
                                        $matchingDeparture->offers = $departure->Offers;
                                        $matchingDeparture->country_name = is_array($departure->Country) ? $departure->Country[0] : $departure->Country;

                                        $matchingDepartures[] = $matchingDeparture;
                                    }
                            }
                    }
                } 
                foreach ($departuresFromDB as $departure) {
                    $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                    $departure->poi_names = $poiNames;
                    $departure->title = ucwords(strtolower($departure->title));
                    $departure->dimage = $departure->image;
                    $departure->image = $departure->image ? generateSignedUrl('package/'.$departure->image) : url('images').'/package-no-image.jpg';
                     $departure->featured = $departure->featured ? 'Best Selling' : '';
                    $departure->colMd = "col-md-4 col-12";
                      $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
             

                    $inclusions = DB::table('inclusions')
                    ->join('inclusion_masters', 'inclusions.icon_inclusion_id', '=', 'inclusion_masters.id')
                    ->where('inclusions.departure_id', $departure->id)
                    ->whereNotNull('inclusions.icon_inclusion_id')
                    ->select('inclusion_masters.name', 'inclusion_masters.icon')
                    ->distinct()
                    ->get();

            foreach ($inclusions as $inclusion) {
                $inclusion->icon = generateSignedUrl('inclusion/'.$inclusion->icon);
            }

                    $departure->inclusions = $inclusions;
                    $destinationId = DB::table('departure_destinations')
                            ->where('departure_id', $departure->id)
                            ->value('destination_id');

                if ($destinationId) {
                    $countryId = DB::table('destinations')
                        ->where('id', $destinationId)
                        ->value('country_id');

                    if ($countryId) {
                        $countryName = DB::table('countries')
                            ->where('id', $countryId)
                            ->value('country_name');

                        $departure->country_name = $countryName ?: null;
                    } else {
                        $departure->country_name = null;
                    }
                } else {
                    $departure->country_name = null;
                }   
                   }

                    $departuresFromDB = json_decode(json_encode($departuresFromDB->toArray()));
                    $departures = array_merge($matchingDepartures,$departuresFromDB);
                    foreach ($departures as $departure) {   
                        if (isset($departure->featured) && $departure->featured == 'Best Selling') {
                            $departure->featured = 1; 
                        } elseif (isset($departure->featured) && $departure->featured != 'Best Selling') {
                            $departure->featured = 0; 
                        }

                        if (isset($departure->BestSellingPackage) && $departure->BestSellingPackage == 1) {
                            $departure->featured = 1; 
                        } elseif (isset($departure->BestSellingPackage) && $departure->BestSellingPackage != 1) {
                            $departure->featured = 0;
                        }
                     }
                    usort($departures, function ($a, $b) {
                        if (is_object($a) && is_object($b)) {
                            if ($a->featured == 1 && $b->featured != 1) {
                                return -1;
                            }
                            if ($a->featured != 1 && $b->featured == 1) {
                                return 1; 
                            }
                        }
                        return 0; 
                    });

                    $currentPage = $request->get('page', 1);
                    $perPage = 6;
                    $totalItems = count($departures);
                    $currentItems = array_slice($departures, ($currentPage - 1) * $perPage, $perPage);
                    $departures = new \Illuminate\Pagination\LengthAwarePaginator(
                        $currentItems,
                        $totalItems,
                        $perPage,
                        $currentPage,
                       ['path' => $request->url(), 'query' => $request->query()]
                    );
                   
                $countries = Country::whereIn('id',$country_id)
                    ->where('status',1)
                    ->where('slug_url','!=','')
                    ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                    ->orderBy('country_exist', 'DESC')
                    ->paginate(8);
                foreach ($countries as $key => $value) {
                    if($value->image != "" || $value->image != null){
                    $value->image = generateSignedUrl('country/'.$value->image);
                    }else{
                        $value->image = url('images').'/poi-no-image.jpg';
                    }
                }
            }
        }
        else
        {
           $departuresFromDB = Departure::join('country_departure_destination_regions','country_departure_destination_regions.departure_id','=','departures.id')
                ->where('country_departure_destination_regions.region_id',$regionId)
                ->where('departures.status', 1)
                ->select('departures.id','departures.title','departures.price_currency','departures.price','departures.price_currency_usd','departures.price_usd','departures.book_online','departures.price_hide_show','departures.no_of_days','departures.no_of_nights','departures.slug_url_pre as slug1','departures.slug_url as slug2','departures.dep_dook_ref_id as slug3','departures.image','departures.created_at','departures.featured')
                ->distinct('departures.created_at')
                ->orderBy('departures.featured','DESC')
                   ->where('dep_type', 'package')
                ->get();

               
            // added by anirudh    
            $departures_id = $departuresFromDB->pluck('id')->toArray();

            $all_destinaton_id = DepartureDestination::whereIn('departure_id',$departures_id)
                ->distinct()
                ->pluck('destination_id')
                ->toArray();

            $region_destination_name = Destination::whereIn('id',$all_destinaton_id)
                ->pluck('dest_name')
                ->sort()
                ->toArray();
            // end by anirudh        

            if ($departures && isset($departures)) {
                $depar = $departures;
                $matchingDepartures = [];
                $iconInclusions = IconInclusion::all()->keyBy('name');
                foreach ($depar as $departure) {    
                        if (!empty($departure->Slug)) {
                            $departure->Name = strtok($departure->Name, '-');
                            $regionName = $region->region_name;
                            $regionID = $region->id; 
                            $normalizedInputName = strtolower(str_replace(' ', '', $regionName));
                            $regionIds = AllCountry::where('country_name', $departure->Country)
                                ->value('region_id');

                                     if ($regionIds == $regionID) {
                                     $poi_names = [];

                                    if (!empty($departure->Itinerary)) {
                                        $departure->Itinerary = array_map(function ($item) use (&$poi_names) {
                                            $poi = $item->Attraction ?? [];
                                            $poi_names = array_merge($poi_names, $poi);
                                            return (object) [
                                                'poi' => $poi,
                                            ];
                                        }, $departure->Itinerary);
                                    }

                                    $departure->poi = array_map(function ($poiItem) {
                                        return $poiItem->Name;
                                    }, array_slice(array_unique($poi_names, SORT_REGULAR), 0, 4)); 

                                    $inclusions = $departure->DepartureDateWithPrice[0]->Inclusion ?? [];                                       
                                    $mappedInclusions = array_map(function ($offer) use ($iconInclusions) {
                                        $icon = isset($iconInclusions[$offer]) ? generateSignedUrl('inclusion/' . $iconInclusions[$offer]->icon) : null;
                                        return (object) [
                                            'name' => $offer,
                                            'icon' => $icon,
                                        ];
                                    }, $inclusions);

                                    $matchingDeparture = new \stdClass();
                                    $matchingDeparture->slug1 = 'group-tours'; 
                                    $matchingDeparture->slug2 = $departure->DookSlug; 
                                    $matchingDeparture->slug3 = $departure->DookDepartureId;
                                    $matchingDeparture->image = $departure->DookImage[0] ?? asset('assets/images/maine-Image.jpg'); 
                                    $matchingDeparture->title = strtok($departure->Name, '-');
                                    $matchingDeparture->featured = $departure->BestSellingPackage;
                                    $matchingDeparture->price = $departure->MinimumPublishedPrice ?? $departure->Price ?? null;
                                    $matchingDeparture->no_of_nights = $departure->DayNight;
                                    $matchingDeparture->poi_names = $departure->poi;
                                    $matchingDeparture->inclusions = $mappedInclusions;
                                    $matchingDeparture->destinations = $departure->Destination;
                                    $matchingDeparture->colMd = 'col-md-4 col-12';
                                    $matchingDeparture->offers = $departure->Offers;
                                    $matchingDeparture->country_name = is_array($departure->Country) ? $departure->Country[0] : $departure->Country;

                                    $matchingDepartures[] = $matchingDeparture;
                                }
                        }
                }
            }      

           foreach ($departuresFromDB as $departure) {
                $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                $departure->poi_names = $poiNames;
                $departure->title = ucwords(strtolower($departure->title));
                $departure->dimage = $departure->image;
                $departure->image = $departure->image ? generateSignedUrl('package/'.$departure->image) : url('images').'/package-no-image.jpg';
                $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';
                $departure->colMd = "col-md-4 col-12";
                $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
                $inclusions = Inclusion::where('departure_id', $departure->id)
                                       ->whereNotNull('icon')
                                       ->select('name', 'icon')
                                       ->distinct()
                                       ->get();

                foreach ($inclusions as $inclusion) {
                    $inclusion->icon = generateSignedUrl('inclusion/'.$inclusion->icon);
                }

                $departure->inclusions = $inclusions;
                $destinationId = DB::table('departure_destinations')
                            ->where('departure_id', $departure->id)
                            ->value('destination_id');

                if ($destinationId) {
                    $countryId = DB::table('destinations')
                        ->where('id', $destinationId)
                        ->value('country_id');

                    if ($countryId) {
                        $countryName = DB::table('countries')
                            ->where('id', $countryId)
                            ->value('country_name');

                        $departure->country_name = $countryName ?: null;
                    } else {
                        $departure->country_name = null;
                    }
                } else {
                    $departure->country_name = null;
                }   
            }            
            $departuresFromDB = json_decode(json_encode($departuresFromDB->toArray()));
            $departures = array_merge($matchingDepartures,$departuresFromDB);
            foreach ($departures as $departure) {   
                if (isset($departure->featured) && $departure->featured == 'Best Selling') {
                    $departure->featured = 1; 
                } elseif (isset($departure->featured) && $departure->featured != 'Best Selling') {
                    $departure->featured = 0; 
                }

                if (isset($departure->BestSellingPackage) && $departure->BestSellingPackage == 1) {
                    $departure->featured = 1; 
                } elseif (isset($departure->BestSellingPackage) && $departure->BestSellingPackage != 1) {
                    $departure->featured = 0;
                }
             }
            usort($departures, function ($a, $b) {
                if (is_object($a) && is_object($b)) {
                    if ($a->featured == 1 && $b->featured != 1) {
                        return -1;
                    }
                    if ($a->featured != 1 && $b->featured == 1) {
                        return 1; 
                    }
                }
                return 0; 
            });

            $currentPage = $request->get('page', 1);
            $perPage = 6;
            $totalItems = count($departures);
            $currentItems = array_slice($departures, ($currentPage - 1) * $perPage, $perPage);
            $departures = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $totalItems,
                $perPage,
                $currentPage,
               ['path' => $request->url(), 'query' => $request->query()]
            );
            $countries = Country::join('country_departure_destination_regions','country_departure_destination_regions.country_id','=','countries.id')
                ->where('country_departure_destination_regions.region_id', $region->id)
                ->where('countries.status',1)
                ->where('countries.slug_url','!=','')
                ->select('countries.id','countries.country_name as countryName','countries.country_exist','countries.slug_url','countries.about_country_slug_url','countries.country_attraction_slug_url','countries.description','countries.image')
                ->distinct()
                ->orderBy('countries.country_exist', 'DESC')
                ->paginate(8);

                if(count($countries)>0) {
                    foreach ($countries as $key => $country_img) {
                        if($country_img->image != "" || $country_img->image != null){
                            $country_img->image = generateSignedUrl('country/'.$country_img->image);
                        }else{
                            $country_img->image = url('images').'/poi-no-image.jpg';
                        }
                    }
                }            
        }

        if ($request->ajax()) {
            return response()->json([
                'departures' => view('frontend.common.tourpackage', compact('departures'))->render(),
                'countries' => view('frontend.countries.countries_card', compact('countries'))->render(),
                'hasMoreDepartures' => $departures->hasMorePages(),
                'hasMoreCountries' => $countries->hasMorePages(),

            ]);
           }
        return view('frontend.regions.regiondetail',compact('region','regions','departures','countries','region_destination_name','common_inquiry_region_name','matchingDepartures'));
    }
}
