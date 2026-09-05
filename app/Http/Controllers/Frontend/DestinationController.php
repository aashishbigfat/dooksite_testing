<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingDeparturePage;
use App\Models\TopDestination;
use App\Models\Destination;
use App\Models\DestinationAirport;
use App\Models\DepartureDestination;
use App\Models\DestinationBestTimeToVisit;
use App\Models\DestinationClimateType;
use App\Models\Departure;
use App\Models\DestinationExperience;
use App\Models\Experience;
use App\Models\Country;
use App\Models\DepartureDestinationPointOfInterest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\IconInclusion;
use App\Models\Inclusion;
use Illuminate\Http\Request;
use App\Models\CountryWisePackage;
use Illuminate\Support\Str;
use DB;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $destination_header = LandingDeparturePage::where('type', 'landing_destination')
            ->select('title', 'sub_title', 'meta_title', 'meta_keywords', 'meta_description', 'description')
            ->first();

        $top_destinations = TopDestination::where(['status' => 1, 'type' => 'departures'])
            ->orderBy('grid_number', 'ASC')
            ->select('id', 'destination_id', 'label_name', 'image', 'grid_number')
            ->paginate(9); // Keep pagination for AJAX

        foreach ($top_destinations as $topDestination) {
            $depName = Destination::where('id', $topDestination->destination_id)
                ->select('id', 'dest_name', 'slug_url')
                ->first();

            $topDestination->destination_name = $depName ? $depName->dest_name : '';
            $topDestination->slug_url = $depName ? $depName->slug_url : '';
            $topDestination->total_dep = $depName
                ? DepartureDestination::where('destination_id', $depName->id)
                    ->distinct('departure_id')
                    ->count('departure_id')
                : 0;

            $experience = TopDestination::where('id', $topDestination->id)
                ->select('experience_name')
                ->first();

            $topDestination->experiences = $experience ? explode(',', $experience->experience_name) : [];
            $topDestination->image = generateSignedUrl('destinations/' . $topDestination->image);
        }

        $remainingDestinationsIds = TopDestination::where(['status' => 1, 'type' => 'departures'])
            ->pluck('destination_id')
            ->toArray();

        $destinations = Destination::whereNotIn('id', $remainingDestinationsIds)
            ->where('status', 1)
            ->where('slug_url', '!=', '')
            ->select('id', 'dest_name', 'slug_url', 'image')
            ->paginate(6); // Keep pagination for AJAX

        $destinations->getCollection()->transform(function ($destination) {
            $destination->total_departure = DepartureDestination::where('destination_id', $destination->id)
                ->distinct('departure_id')
                ->count('departure_id');

            $experienceIds = DestinationExperience::where('destination_id', $destination->id)
                ->distinct()
                ->pluck('experience_id')
                ->toArray();

            $experienceNames = Experience::whereIn('id', $experienceIds)
                ->where('status', 1)
                ->limit(4)
                ->distinct()
                ->pluck('experience_name')
                ->toArray();

            $destination->experiences = $experienceNames;
            $destination->image = $destination->image
                ? generateSignedUrl('poi/' . $destination->image)
                : url('images') . 'assets/no-image.jpg';

            return $destination;
        });

        if ($request->ajax()) {
            return response()->json([
                'top_destinations' => view('frontend.destination.destination_card', ['top_destinations' => $top_destinations])->render(),
                'destinationData' => view('frontend.destination.destinationdata', ['destinationData' => $destinations])->render(),
                'topHasMorePages' => $top_destinations->hasMorePages(),
                'destinationHasMorePages' => $destinations->hasMorePages(),
            ]);
        }

        return view('frontend.destination.destination', compact('destination_header', 'top_destinations', 'destinations'));
    }

    public function destinationDetail(Request $request, $slug_url)
    {
        $destination = Destination::where('slug_url', $slug_url)->where('status', 1)
            ->select('id', 'dest_name', 'country_name', 'description', 'drives_on', 'currency_code', 'currency_symbol', 'latitude', 'longitude', 'title', 'sub_title', 'header_title', 'header_sub_title', 'tour_sub_title', 'experience_sub_title', 'slug_url', 'attraction_sub_title', 'event_sub_title', 'restaurant_sub_title', 'trip_sub_title', 'trip_description', 'meta_title', 'meta_keywords', 'meta_description', 'banner_image', 'image')
            ->first();
        if (!$destination) {
            return redirect('/');
        }
        if ($destination->header_title == '' || $destination->header_title == '') {
            $destination->header_title = $destination->dest_name . ' ' . "Tour Packages";
        }
        if ($destination->header_sub_title == '' || $destination->header_sub_title == '') {
            $destination->header_sub_title = "We create travel memories that you would want to revisit!";
        }
        $country_id = Destination::where('id', $destination->id)
            ->value('country_id');
        $country_data = Country::where('id', $country_id)
            ->value('flag');
        $destination->country_flag = generateSignedUrl('flag/' . $country_data);
        $airports = DestinationAirport::where('destination_id', $destination->id)
            ->pluck('airport_name')
            ->toArray();
        $airports = implode(', ', $airports);
        $best_time_to_visits = DestinationBestTimeToVisit::where('destination_id', $destination->id)
            ->pluck('name')
            ->toArray();
        $best_time_to_visits = implode(', ', $best_time_to_visits);
        $climate_types = DestinationClimateType::where('destination_id', $destination->id)
            ->pluck('name')
            ->toArray();
        $climate_types = implode(', ', $climate_types);
        $destination->airports = $airports;
        $destination->bestTimeVisits = $best_time_to_visits;
        $destination->climateTypes = $climate_types;

        $package_ids = DepartureDestination::where('destination_id', $destination->id)
            ->distinct()
            ->pluck('departure_id')
            ->toArray();
        $departuresFromDB = Departure::whereIn('id', $package_ids)
            ->where('status', 1)
            ->where('dep_type', 'package')
            ->select('id', 'title', 'price_currency', 'price', 'price_currency_usd', 'price_usd', 'book_online', 'price_hide_show', 'no_of_days', 'no_of_nights', 'slug_url_pre as slug1', 'slug_url as slug2', 'dep_dook_ref_id as slug3', 'dep_type', 'image', 'featured')
            ->orderBy('featured', 'DESC')
            ->get();
       
        if (count($departuresFromDB) > 0) {
             foreach ($departuresFromDB as $departure) {
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
            $departure->poi_names = $poiNames;
            $departure->title = ucwords(strtolower($departure->title));
            $departure->dimage = $departure->image;
            $departure->image = $departure->image ? generateSignedUrl('package/'.$departure->image) : url('images').'/package-no-image.jpg';
            $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';
            $departure->colMd = "col-md-3 col-12";
              $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
             $inclusions = DB::table('inclusions')
                    ->join('inclusion_masters', 'inclusions.icon_inclusion_id', '=', 'inclusion_masters.id')
                    ->where('inclusions.departure_id', $departure->id)
                    ->whereNotNull('inclusions.icon_inclusion_id')
                    ->select('inclusion_masters.name', 'inclusion_masters.icon')
                    ->distinct()
                    ->get();

            foreach ($inclusions as $inclusion) {
                $inclusion->icon = generateSignedUrl("inclusion/".$inclusion->icon);
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
           $matchingDepartures = [];
           if ($departures && isset($departures)) {
                $depar = $departures;
                $iconInclusions = IconInclusion::all()->keyBy('name');
                foreach ($depar as $departure) {    
                        if (!empty($departure->Slug)) {
                            $departure->Name = strtok($departure->Name, '-');
                            $countriesName = $destination->dest_name;
                            $countriesID = $destination->id; 
                            $normalizedInputName = strtolower(str_replace(' ', '', $countriesName));
                            $regionIds = Destination::where('dest_name', $departure->Destination)
                                ->value('id');

                                     if ($regionIds == $countriesID) {
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
                                            $icon = isset($iconInclusions[$offer]) ? generateSignedUrl("inclusion/" . $iconInclusions[$offer]->icon) : null;
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
                                    $matchingDeparture->colMd = 'col-md-3 col-12';
                                    $matchingDeparture->offers = $departure->Offers;
                                     $matchingDeparture->country_name = is_array($departure->Country) ? $departure->Country[0] : $departure->Country;

                                    $matchingDepartures[] = $matchingDeparture;
                                }
                        }
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
            $perPage = 8;
            $totalItems = count($departures);
            $currentItems = array_slice($departures, ($currentPage - 1) * $perPage, $perPage);
            $departures = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $totalItems,
                $perPage,
                $currentPage,
               ['path' => $request->url(), 'query' => $request->query()]
            );
        

        //Experience setion

        $experience_id = DestinationExperience::where('destination_id', $destination->id)
            ->distinct()
            ->pluck('experience_id')
            ->toArray();
        $experiences = Experience::whereIn('id', $experience_id)
            ->select('id', 'experience_name', 'slug_url', 'image')
            ->where('status', 1)
            ->get();
        if (count($experiences) > 0) {
            foreach ($experiences as $key => $value) {
                if ($value->image != "" || $value->image != null) {
                    $value->image = generateSignedUrl('experience/' . $value->image);
                } else {
                    $value->image = url('images') . '/event-no-image.jpg';
                }
            }
        }

        //Pois Section
        $poi_ids = DepartureDestinationPointOfInterest::where('destination_id', $destination->id)
            ->distinct()
            ->pluck('reference_id')
            ->toArray();
      

        $pointOfInterest = DepartureDestinationPointOfInterest::whereIn('reference_id', $poi_ids)
            ->where('status', 1)
            ->select(
                'poi_name',
                DB::raw('MIN(destination_id) as destination_id'),
                DB::raw('MIN(reference_id) as poiId'),
                DB::raw('MIN(latitude) as latitude'),
                DB::raw('MIN(longitude) as longitude'),
                DB::raw('MIN(image) as image'),
                DB::raw('MIN(description) as description')
            )
            ->groupBy('poi_name')
            ->get();

        if (count($pointOfInterest) > 0) {
            // Counted for the whole set in one grouped query instead of a
            // query per POI plus a further query per linked departure inside
            // the loop below.
            $poiCounts = poiDepartureCountsUnfiltered($pointOfInterest->pluck('poiId')->all());

            foreach ($pointOfInterest as $key => $value) {

                $value->total_departures = $poiCounts[$value->poiId]['total'] ?? 0;

                $make_poi_url = str_replace(array('\'', '"', ',', ';', '<', '>', '&', '$', '(', ')', '}', '{', '[', ']', '%', '+', '_', '.', '^', '#', '@', '*', '’'), '', $value->poi_name);
                $strlower = Str::lower($make_poi_url);
                $arr = explode(' ', $strlower);
                $str = implode('-', $arr);
                $mainstr = str_replace(array('--', '---', '----'), '-', $str);
                //$poi_url = "poi/".$mainstr.'/'.$value->poiId;
                $poi_url = $mainstr;
                $value->poi_url = $poi_url;

                $value->featured_departure = $poiCounts[$value->poiId]['featured'] ?? 0;
                if ($value->image != "" || $value->image != null) {
                    $value->image = generateSignedUrl('poi/' . $value->image);
                } else {
                    $value->image = url('images') . '/package-no-image.jpg';
                }
            }
        }
        $destination_name_from_destination_page = $destination->dest_name;
         if ($request->ajax()) {
            return response()->json([
                'departures' => view('frontend.common.tourpackage', compact('departures'))->render(),
                // 'countries' => view('frontend.countries.countries_card', compact('countries'))->render(),
                'hasMoreDepartures' => $departures->hasMorePages(),
                // 'hasMoreCountries' => $countries->hasMorePages(),

            ]);
        }
        

            return view('frontend.destination.destination_details', compact('destination_name_from_destination_page','destination', 'departures','experiences', 'pointOfInterest','matchingDepartures'));

    }

    public function countrywisepackage(Request $request, $slug)
    {
        $package = CountryWisePackage::where('slug', $slug)->firstOrFail();

        // Explode the comma-separated IDs
        $departureIds = explode(',', $package->departure_ids);

        // Fetch all matching departures in one query
        $departures = Departure::whereIn('id', $departureIds)->where('status', '1')->select('departures.id', 'departures.title', 'departures.price_currency', 'departures.price', 'departures.price_currency_usd', 'departures.price_usd', 'departures.book_online', 'departures.price_hide_show', 'departures.no_of_days', 'departures.no_of_nights', 'departures.slug_url_pre as slug1', 'departures.slug_url as slug2', 'departures.dep_dook_ref_id as slug3', 'departures.image', 'departures.created_at', 'departures.featured')->get();
         foreach ($departures as $departure) {
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
            $departure->poi_names = $poiNames;
            $wordLimit = 4;
            $departure->title = ucwords(strtolower(implode(' ', array_slice(explode(' ', $departure->title), 0, $wordLimit))));
            $departure->dimage = $departure->image;
            $departure->image = $departure->image ? env('AWS_BUCKET_URL') . '/package/' . $departure->image : url('images') . '/package-no-image.jpg';
            $departure->featured = $departure->featured ? 'Best Selling' : ''; 
             $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
   
             $inclusions = DB::table('inclusions')
                    ->join('inclusion_masters', 'inclusions.icon_inclusion_id', '=', 'inclusion_masters.id')
                    ->where('inclusions.departure_id', $departure->id)
                    ->whereNotNull('inclusions.icon_inclusion_id')
                    ->select('inclusion_masters.name', 'inclusion_masters.icon')
                    ->distinct()
                    ->get();
            foreach ($inclusions as $inclusion) {
                $inclusion->icon = env('AWS_BUCKET_URL') . "/inclusion/" . $inclusion->icon;
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

        return view('frontend.country_wise_packages', compact('package', 'departures'));
    }

}
