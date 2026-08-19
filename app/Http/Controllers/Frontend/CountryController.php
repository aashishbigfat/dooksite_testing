<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\LandingDeparturePage;
use App\Models\DepartureDestinationPointOfInterest;
use App\Models\ActivityPointOfInterest;
use App\Models\DestinationExperience;
use App\Models\CountryExistingPoi;
use Illuminate\Support\Facades\Log;
use App\Models\CountryBestTimeToVisit;
use App\Models\CountryClimateType;
use App\Models\CountryDeparture;
use App\Models\Departure;
use App\Models\SlugMaster;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\AllCountry;
use App\Models\CountryWisePackage;
use App\Models\Inclusion;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\IconInclusion;
use DB;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index(Request $request) {
        // $countries = Country::where('status',1)->where('slug_url','!=','')->select('id', 'country_name as countryName', 'country_exist', 'slug_url', 'about_country_slug_url', 'country_attraction_slug_url', 'description', 'image')->orderBy('country_exist', 'DESC')->distinct()->paginate(12);
         $countries = Country::where('status', 1)
            ->distinct()
            ->where('slug_url', '!=', '')
            ->select('id', 'country_name as countryName', 'country_exist', 'slug_url', 'about_country_slug_url', 'country_attraction_slug_url', 'description', 'image')
            ->orderByRaw("CASE WHEN about_country_slug_url IS NOT NULL AND about_country_slug_url != '' THEN 0 ELSE 1 END")
            ->orderBy('country_exist', 'DESC')
            ->orderBy('id', 'ASC')
            ->paginate(12);
        if(count($countries)>0) {
                    foreach ($countries as $key => $country_img) {
                        if($country_img->image != "" || $country_img->image != null){
                            $country_img->image = generateSignedUrl('country/'.$country_img->image);
                        }else{
                            $country_img->image = './assets'.url('images').'/no-image.jpg';
                        }
                    }
                }
                 if ($request->ajax()) {
            return response()->json([
                'countries' => view('frontend.countries.countries_card', compact('countries'))->render(),
                'hasMoreCountries' => $countries->hasMorePages(),
            ]);
        }
       
        $country_header = LandingDeparturePage::where('type', 'landing_country')
            ->select('title', 'sub_title', 'meta_title', 'meta_keywords', 'meta_description', 'description')
            ->first();
        return view('frontend.countries.index',compact('countries','country_header'));
    }
    public function paginateCountries(Request $request)
    {
        if ($request->ajax()) {
            $countries = Country::where('status', 1)
                ->where('slug_url', '!=', '')
                ->select('id', 'country_name as countryName', 'country_exist', 'slug_url', 'about_country_slug_url', 'country_attraction_slug_url', 'description', 'image')
                ->orderBy('country_exist', 'DESC')
                ->distinct()
                ->paginate(12);

            return view('frontend.countries.countries_card', compact('countries')); // Partial view for countries
        }
    }
    // Country About Method
    public function countryAbout(Request $request,$slug_url) {
         $country = Country::where('about_country_slug_url', $slug_url)
            ->first();

        if (!$country) {
            return redirect('/');
        }
        $country->visa_on_arrival = $country->visa_on_arrival ? 'Yes' : 'No';
        $relatedData = Country::leftJoin('destinations', 'destinations.id', '=', DB::raw('FIND_IN_SET(destinations.id, countries.major_destinations)'))
            ->leftJoin('country_demonyms', 'country_demonyms.country_id', '=', 'countries.id')
            ->leftJoin('country_official_languages', 'country_official_languages.country_id', '=', 'countries.id')
            ->leftJoin('country_best_time_to_visits', 'country_best_time_to_visits.country_id', '=', 'countries.id')
            ->leftJoin('country_ethnicities', 'country_ethnicities.country_id', '=', 'countries.id')
            ->leftJoin('country_religions', 'country_religions.country_id', '=', 'countries.id')
            ->leftJoin('country_climate_types', 'country_climate_types.country_id', '=', 'countries.id')
            ->where('countries.id', $country->id)
            ->first();
        if ($relatedData) {
            $country->dest_name = implode(', ', array_filter(explode(',', $relatedData->dest_name)));
            $country->demonym_name = $relatedData->demonym_name ? implode(', ', explode(',', $relatedData->demonym_name)) : '';
            $country->language_name = $relatedData->language_name ? implode(', ', explode(',', $relatedData->language_name)) : '';
            $country->name = $relatedData->name ? implode(', ', explode(',', $relatedData->name)) : '';
            $country->ethnicity_name = $relatedData->ethnicity_name ? implode(', ', explode(',', $relatedData->ethnicity_name)) : '';
            $country->religion_name = $relatedData->religion_name ? implode(', ', explode(',', $relatedData->religion_name)) : '';
            $country->climate_type_name = $relatedData->climate_type_name ? implode(', ', explode(',', $relatedData->climate_type_name)) : '';
        }
        return view('frontend.countries.country_about', compact('country'));
    }

    public function countrySlug(Request $request,$slug){
        $check_url = SlugMaster::where('slug_name', $slug)->select('module_name')->first();
        $username = env('AGENT_CONNECT_USERNAME');
        $password = env('AGENT_CONNECT_PASSWORD');
        $headerArray = [
            'Username: ' . $username,
            'Password: ' . $password,
        ];

        $baseUrl = 'https://agent.dookinternational.com/api';
        $url = $baseUrl . '/departure/group-departure';
        $cacheKey = 'group-departures';

        try {
            $departures = Cache::remember($cacheKey, 300, function () use ($url, $headerArray) {
                // Initialize cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray); 

                $result = curl_exec($ch);

                if (curl_errno($ch)) {
                    // If there's an error, log it and return null
                    Log::error('cURL Error: ' . curl_error($ch));
                    curl_close($ch);
                    return null;
                }
                curl_close($ch);

                $data1 = json_decode($result);
                if (isset($data1->Result)) {
                    return $data1->Result;
                } else {
                    Log::error('API response structure has changed or no result found.');
                    return null;
                }
            });


        } catch (Exception $e) {
            Log::error("API Error", ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to retrieve data'], 500);
        } 
         if ($check_url) {
            if ($check_url->module_name == "country_tour_page") {
                 $countries = Country::where('slug_url', $slug)->select('id', 'country_name as countryName', 'title', 'sub_title as subTitle', 'official_name as officialName', 'capital', 'largest_city as largestCity', 'iso_3 as iso', 'currency', 'currency_symbol as currencySymbol', 'currency_code as currencyCode', 'national_language as nationalLanguage', 'drives_on as driveSide', 'area_unit as areaUnit', 'area', 'population', 'description', 'text_1', 'image_1', 'text_2', 'image_2', 'text_3', 'image_3', 'text_4', 'banner_image', 'flag', 'meta_title', 'meta_keywords', 'meta_description','mobile_banner_image','slug_url')->first(); 
                 $country_wise = CountryWisePackage::where('country_id', $countries->id)->get();
                 $comonInquiryCountry = $countries->countryName;
                $commoninquirycountryid = [$countries->id];
                $common_inquiry_destination_name = DB::table('destinations')->whereIn('country_id', $commoninquirycountryid)->distinct()->pluck('dest_name')->toArray();
                 if ($countries) {
                     $countries->image_1 = generateSignedUrl('country/' . $countries->image_1);
                    $countries->image_2 = generateSignedUrl('country/' . $countries->image_2);
                    $countries->image_3 = generateSignedUrl('country/' . $countries->image_3);
                  $countries->bestTimeToVisits = CountryBestTimeToVisit::where('country_id', $countries->id)
                        ->get();
                    $countries->climateTypes = CountryClimateType::select('climate_type_name as name')
                        ->where('country_id', $countries->id)
                        ->get();
                    $departuresFromDB = DB::table('departures as d')
                        ->join('country_departures as cd', 'cd.departure_id', '=', 'd.id')
                        ->where('cd.country_id', $countries->id)->where('d.status', 1)->where('d.dep_type', 'package')
                        ->select('d.id', 'd.title', 'd.price_currency', 'd.price', 'd.price_currency_usd', 'd.price_usd', 'd.book_online', 'd.price_hide_show', 'd.slug_url_pre as slug1', 'd.slug_url as slug2', 'd.dep_dook_ref_id as slug3', 'd.no_of_days', 'd.no_of_nights', 'd.image', 'd.featured', 'd.dep_type')
                        ->distinct()->orderBy('d.featured', 'DESC')->get();

                        foreach ($departuresFromDB as $departure) {
                        $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                        $departure->poi_names = $poiNames;
                        $wordLimit = 4;
                        $departure->title = ucwords(strtolower(implode(' ', array_slice(explode(' ', $departure->title), 0, $wordLimit))));
                        $departure->dimage = $departure->image;
                        $departure->image = $departure->image ? generateSignedUrl('package/' . $departure->image) : url('images') . '/package-no-image.jpg';
                        $departure->featured = $departure->featured ? 'Best Selling' : ''; 
                         $departure->no_of_nights = "{$departure->no_of_nights} Nights {$departure->no_of_days} Days ";
               
                        $inclusions = DB::table('inclusions')
                    ->join('inclusion_masters', 'inclusions.icon_inclusion_id', '=', 'inclusion_masters.id')
                    ->where('inclusions.departure_id', $departure->id)
                    ->whereNotNull('inclusions.icon_inclusion_id')
                    ->select('inclusion_masters.name', 'inclusion_masters.icon')
                    ->distinct()
                    ->get();
                        foreach ($inclusions as $inclusion) {
                            $inclusion->icon = generateSignedUrl("inclusion/" . $inclusion->icon);
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
                     if ($departures && isset($departures)) {
                            $depar = $departures;
                            $matchingDepartures = [];
                            $iconInclusions = IconInclusion::all()->keyBy('name');
                            foreach ($depar as $departure) {    
                                if (!empty($departure->Slug)) {
                                    $departure->Name = strtok($departure->Name, '-');
                                    $countriesName = $countries->countryName;
                                    $countriesID = $countries->id; 
                                    $normalizedInputName = strtolower(str_replace(' ', '', $countriesName));
                                    $regionIds = Country::where('country_name', $departure->Country)
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
                             if (count($departures) > 0) {
         
                        //new code optimize
                        $destinationQuery = DB::table('departure_destinations as dd')->join('destinations as d', 'dd.destination_id', '=', 'd.id')->distinct()->pluck('d.dest_name');
                       
                        $country_destination_name = $destinationQuery
                            // ->whereIn('dd.departure_id', $departure_id)
                            ->sort()
                            ->toArray(); 
                        $countryDestinations = ['Azerbaijan' => 'Baku', 'Kazakhstan' => 'Almaty', 'Russia' => 'Moscow', 'Uzbekistan' => 'Tashkent', 'Kyrgyzstan' => 'Bishkek', 'Armenia' => 'Yerevan', 'Georgia' => 'Tbilisi', 'Turkey' => 'Istanbul', 'Serbia' => 'Belgrade', 'Belarus' => 'Minsk', 'Greece' => 'Athens', 'Egypt' => 'Cairo', 'Finland' => 'Helsinki', 'Indonesia' => 'Bali', 'Switzerland' => 'Bern', 'United Kingdom' => 'London'];
                        $dest_for_select = isset($countryDestinations[$comonInquiryCountry]) ? $countryDestinations[$comonInquiryCountry] : '';
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
                    }
                    if ($request->ajax()) {
                        return response()->json([
                            'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                            'hasMorePages' => $departures->hasMorePages()
                        ]);
                    }
                return view('frontend.countries.country_departure', compact('countries', 'departures','comonInquiryCountry','common_inquiry_destination_name','country_wise','matchingDepartures'));
            }elseif ($check_url->module_name == "country_attraction_page") {
                $country_id = Country::where('country_attraction_slug_url', $slug)->distinct()->pluck('id')->first();

                if ($country_id) {
                    $country_poi_detail = Country::select(
                        'country_name as countryName', 'attraction_title as title', 'attraction_sub_title as subTitle', 
                        'attraction_heading', 'attraction_description', 'flag', 'banner_image_attraction as banner_image', 
                        'attraction_meta_title as meta_title', 'attraction_meta_keywords as meta_keywords', 
                        'attraction_meta_description as meta_description', 'country_attraction_slug_url'
                    )->find($country_id);

                    $get_dest_ids = Destination::where('country_id', $country_id)->distinct()->pluck('id')->toArray();
                    $poi_exi_unique = [];
                    $all_pois = collect(); // Single variable to store all unique POIs

                    if ($get_dest_ids) {
                        $poi_ids = DepartureDestinationPointOfInterest::whereIn('destination_id', $get_dest_ids)
                            ->distinct('reference_id')
                            ->pluck('reference_id')
                            ->toArray();

                        $country_pois = DepartureDestinationPointOfInterest::whereIn('reference_id', $poi_ids)
                            ->select('reference_id as poiId', 'destination_id', 'poi_name', 'latitude', 'longitude', 'poi_type', 
                                     'phone', 'website', 'rating', 'openhours', 'description', 'address', 'image')
                            ->get();

                        foreach ($country_pois as $poi) {
                            $normalized_poi_name = strtolower(trim($poi->poi_name));
                            if (!in_array($normalized_poi_name, $poi_exi_unique)) {
                                $words = explode(' ', $poi->description);
                                $poi->description = $poi->description;
                                $poi->image = $poi->image ? generateSignedUrl('poi/' . $poi->image) : url('assets/images') . '/no_image.jpg';
                                $poi->poi_url = "poi/" . Str::slug($poi->poi_name, '-') . "/{$poi->poiId}";
                                $poi_exi_unique[] = $normalized_poi_name;
                                $all_pois->push($poi);
                            }
                        }

                       
                    }
                     $experience_ids = DestinationExperience::whereIn('destination_id', $get_dest_ids)
                            ->distinct()
                            ->pluck('experience_id')
                            ->toArray();

                        $experience_row = Experience::whereIn('id', $experience_ids)
                            ->select('id', 'experience_name as name', 'slug_url as slug', 'image')
                            ->get()
                            ->map(function ($exp) {
                                $exp->image = $exp->image ? generateSignedUrl('experience/' . $exp->image) : url('images') . '/no-image.jpg';
                                return $exp;
                            })
                            ->values();

                    $existing_pois = CountryExistingPoi::where('country_id', $country_id)
                        ->whereNotIn('poi_name', $poi_exi_unique)
                        ->select('poi_name', 'latitude', 'longitude', 'poi_type', 'rating', 'description', 'address', 'image')
                        ->get();

                    foreach ($existing_pois as $pois_e) {
                        $words = explode(' ', $pois_e->description);
                        $pois_e->description = $pois_e->description;
                        $pois_e->image = $pois_e->image ? generateSignedUrl('poi/' . $pois_e->image) : url('assets/images') . '/no_image.jpg';
                        $all_pois->push($pois_e);
                    }

                    // Paginate the combined collection
                    // $paginated_pois = new \Illuminate\Pagination\LengthAwarePaginator(
                    //     $all_pois->forPage(request()->get('page', 1), 9), 
                    //     $all_pois->count(), 
                    //     9, 
                    //     request()->get('page', 1),
                    //     ['path' => request()->url()]
                    // );

                    // if (request()->ajax()) {
                    //     return response()->json([
                    //         'view' => view('frontend.poi.partial_card', compact('paginated_pois'))->render(),
                    //         'hasMorePages' => $paginated_pois->hasMorePages()
                    //     ]);
                    // }

                    return view('frontend.countries.country_attraction', compact('country_poi_detail', 'all_pois', 'experience_row'));
                }
            }elseif ($check_url->module_name == "country_group_page") {
                 // Fetch the country details
                $countries = DB::table('countries')
                    ->where('country_group_slug_url', $slug)
                    ->select('id', 'country_name as countryName', 'group_title', 'group_sub_title as subTitle', 
                             'group_description', 'edit_banner_image_group as banner_image', 'flag', 
                             'group_meta_title as meta_title', 'group_meta_keywords as meta_keywords', 
                             'group_meta_description as meta_description')
                    ->first();

                if ($countries) {   
                    $comonInquiryCountry = $countries->countryName;
                    $commoninquirycountryid = [$countries->id];

                    $common_inquiry_destination_name = DB::table('destinations')
                        ->whereIn('country_id', $commoninquirycountryid)
                        ->distinct()
                        ->pluck('dest_name')
                        ->toArray();

                    // Fetch departure IDs associated with this country
                    $departure_id = DB::table('country_departures')
                        ->where('country_id', $countries->id)
                        ->distinct()
                        ->pluck('departure_id')
                        ->toArray();

                    // API Header
                    $header = [
                        "Username" => env('AGENT_CONNECT_USERNAME'),
                        "Password" => env('AGENT_CONNECT_PASSWORD')
                    ];
                    $apiUrl = env('AGENT_CONNECT_API_BASE_URL') . "/departure/group-departure";

                    try {
                        $toursResponse = Cache::remember('group-tours', 300, function () use ($apiUrl, $header) {
                            $response = Http::retry(3, 100)
                                ->withHeaders($header)
                                ->get($apiUrl);
                            if ($response->successful()) {
                                return json_decode($response->getBody()->getContents(), true);
                            } else {
                                Log::error("API request failed", ['url' => $apiUrl, 'status' => $response->status()]);
                                return null;
                            }
                        });

                        $departures = $toursResponse !== null ? collect($toursResponse['Result']) : collect();
                    } catch (Exception $e) {
                        Log::error("API Error", ['message' => $e->getMessage(), 'Api Url' => $apiUrl]);
                        $departures = collect();
                    }

                    // Load Icon Inclusions
                    $iconInclusions = IconInclusion::all()->keyBy('name');

                    // Filter departures based on the country match
                    $departures = $departures
                        ->filter(function ($tour) use ($countries) {
                            return in_array($countries->countryName, $tour['Country'] ?? []);
                        })
                        ->map(function ($tour) use ($iconInclusions) {
                            $poi_names = [];
                            $tour['Itinerary'] = array_map(function ($item) use (&$poi_names) {
                                $poi = $item['Attraction'] ?? [];
                                $poi_names = array_merge($poi_names, $poi);
                                return (object) ['poi' => $poi];
                            }, $tour['Itinerary']);

                            $tour['poi'] = array_map(function ($poiItem) {
                                return $poiItem['Name'];
                            },  array_slice(array_unique($poi_names, SORT_REGULAR), 0, 4)); 

                            $inclusions = $tour['DepartureDateWithPrice'][0]['Inclusion'] ?? [];

                            $mappedInclusions = array_map(function ($offer) use ($iconInclusions) {
                                $icon = isset($iconInclusions[$offer]) ? generateSignedUrl("inclusion/" . $iconInclusions[$offer]->icon) : null;
                                return (object) ['name' => $offer, 'icon' => $icon];
                            }, $inclusions);

                            return (object) [
                                'slug1' => 'group-tours',
                                'slug2' => $tour['DookSlug'],
                                'slug3' => $tour['DookDepartureId'],
                                'image' => $tour['DookImage'][0] ?? asset('assets/images/maine-Image.jpg'),
                                'title' => strtok($tour['Name'], '-'),
                                'featured' => $tour['BestSellingPackage'] ?? false,
                                'price' => $tour['MinimumPublishedPrice'] ?? $tour['Price'] ?? nulll,
                                'no_of_nights' => $tour['DayNight'] ?? 0,
                                'poi_names' => $tour['poi'],
                                'inclusions' => $mappedInclusions,
                                'destinations' => $tour['Destination'] ?? [],
                                'colMd' => 'col-md-3 col-12',
                                'offers' => $tour['Offers'] ?? [],
                                'country_name' => $tour['Country'][0] ?? null,
                            ];
                        });
                }

                $departures = $departures->sortByDesc('featured');
                $perPage = 6;
                $currentPage = $request->get('page', 1);
                $departures = new \Illuminate\Pagination\LengthAwarePaginator(
                    $departures->forPage($currentPage, $perPage),
                    $departures->count(),
                    $perPage,
                    $currentPage,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
                if ($departures->isEmpty()) {
                    $noPackagesFoundMessage = "No packages found in the selected price range.";
                }
                // Return JSON response for AJAX
                if ($request->ajax()) {
                    return response()->json([
                        'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                        'hasMorePages' => $departures->hasMorePages()
                    ]);
                }
                // Return normal view
                return view('frontend.countries.country_group', compact('countries', 'departures', 'comonInquiryCountry', 'common_inquiry_destination_name'));                
            } elseif ($check_url->module_name == "country_experience_page") {
                $countries = Country::where('country_experience_slug_url', $slug)->select('id', 'country_name as countryName', 'experience_title', 'experience_sub_title as subTitle', 'experience_description', 'edit_banner_image_experience as banner_image', 'flag', 'experience_meta_title as meta_title', 'experience_meta_keywords as meta_keywords', 'experience_meta_description as meta_description')->first();
                $comonInquiryCountry = $countries->countryName;
                $commoninquirycountryid = [$countries->id];
                $common_inquiry_destination_name = Destination::whereIn('country_id', $commoninquirycountryid)->pluck('dest_name')->toArray();
                $departure_id = CountryDeparture::where('country_id', $countries->id)->distinct()->pluck('departure_id')->toArray();
                $departuresFromDB = Departure::select('id', 'title', 'price_currency', 'price', 'price_currency_usd', 'price_usd', 'book_online', 'price_hide_show', 'slug_url_pre as slug1', 'slug_url as slug2', 'dep_dook_ref_id as slug3', 'no_of_days', 'no_of_nights', 'image', 'featured')->whereIn('id', $departure_id)->orderBy('featured', 'DESC')->where('dep_type', 'package')->where('status', 1)->get();
                  if ($departures && isset($departures)) {
                            $depar = $departures;
                            $matchingDepartures = [];
                            $iconInclusions = IconInclusion::all()->keyBy('name');
                            foreach ($depar as $departure) {    
                                if (!empty($departure->Slug)) {
                                    $departure->Name = strtok($departure->Name, '-');
                                    $countriesName = $countries->countryName;
                                    $countriesID = $countries->id; 
                                    $normalizedInputName = strtolower(str_replace(' ', '', $countriesName));
                                    $regionIds = Country::where('country_name', $departure->Country)
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
                                        },  array_slice(array_unique($poi_names, SORT_REGULAR), 0, 4)); 

                                        $inclusions = $departure->DepartureDateWithPrice[0]->Inclusion ?? null;                                       
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
                                        $matchingDeparture->image = $departure->DookImage[1] ?? asset('assets/images/maine-Image.jpg'); 
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
                if (count($departuresFromDB) > 0) {
                    foreach ($departuresFromDB as $departure) {
                        $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                        $departure->poi_names = $poiNames;
                        $wordLimit = 4;
                        $departure->title = ucwords(strtolower(implode(' ', array_slice(explode(' ', $departure->title), 0, $wordLimit))));
                        $departure->dimage = $departure->image;
                        $departure->image = $departure->image ? generateSignedUrl('package/' . $departure->image) : url('images') . '/package-no-image.jpg';
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
                            $inclusion->icon = generateSignedUrl("inclusion/" . $inclusion->icon);
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
                    
                if ($request->ajax()) {
                        return response()->json([
                            'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                            'hasMorePages' => $departures->hasMorePages()
                        ]);
                    }

                    return view('frontend.countries.country_experience', compact('countries', 'departures', 'comonInquiryCountry', 'common_inquiry_destination_name','matchingDepartures'));
            }
            else{
                 return redirect('/');
            }
        }
    }
}
