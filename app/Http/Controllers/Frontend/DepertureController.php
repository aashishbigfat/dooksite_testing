<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\DepartureDestinationPointOfInterest;
use App\Models\DepartureDestination;
use App\Models\IconInclusion;
use App\Models\Itinerary;
use App\Models\Inclusion;
use App\Models\Destination;
use App\Models\Exclusion;
use App\Models\Visa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Departure;
use DB;

class DepertureController extends Controller
{
    public function internationalTourPackages(Request $request)
    {
           // Get maximum package price
           $maxPrice1 = Departure::max('departures.price');

           // Get min and max price filter
           $minPrice = request()->get('min_price', 0);
           $maxPrice = request()->get('max_price', $maxPrice1);

           // Get flight filter
           $flightFilter = $request->get('flight_filter');

           // Get selected destinations
           $selectedDestinations = explode(',', $request->get('destinations', ''));

           $keyword = request()->query('keyword', null);
           $cacheKey = 'international_tour_packages_' . md5($keyword . request()->get('page', 1) . $flightFilter . implode(',', $selectedDestinations) ."_{$minPrice}_{$maxPrice}");
       
           $departures = Cache::remember($cacheKey, 60, function () use ($minPrice, $maxPrice, $keyword, $flightFilter, $selectedDestinations) {
               $query = Departure::join('departure_destinations', 'departure_destinations.departure_id', '=', 'departures.id')
                   ->join('destinations', 'destinations.id', '=', 'departure_destinations.destination_id')
                   ->join('country_departures', 'country_departures.departure_id', '=', 'departures.id')
                   ->join('countries', 'countries.id', '=', 'country_departures.country_id')
                   ->where('departures.status', 1)
                   ->where('departures.dep_type', 'package')
                   ->where('country_departures.country_id', '!=', 33)
                   ->whereBetween('departures.price', [$minPrice, $maxPrice]); // Filter by price range

               if (!is_null($keyword)) {
                   $query->where(function ($query) use ($keyword) {
                       $query->where('destinations.dest_name', 'LIKE', '%' . $keyword . '%')
                             ->orWhere('countries.country_name', 'LIKE', '%' . $keyword . '%')
                             ->orWhere('countries.continent', 'LIKE', '%' . $keyword . '%');
                   });
               }

               if ($flightFilter === 'with_flight') {
                   $query->whereExists(function ($subQuery) {
                       $subQuery->select(DB::raw(1))
                           ->from('inclusions')
                           ->whereColumn('inclusions.departure_id', 'departures.id')
                           ->where('inclusions.name', 'Flights');
                   });
               } elseif ($flightFilter === 'without_flight') {
                   $query->whereNotExists(function ($subQuery) {
                       $subQuery->select(DB::raw(1))
                           ->from('inclusions')
                           ->whereColumn('inclusions.departure_id', 'departures.id')
                           ->where('inclusions.name', 'Flights');
                   });
               }
                if (!empty($selectedDestinations) && $selectedDestinations[0] !== '') {
                   $query->whereIn('destinations.dest_name', $selectedDestinations);
                 }
       
               $query->select('departures.id', 'departures.title', 'departures.price_currency', 'departures.price', 'departures.price_currency_usd', 'departures.price_usd', 'departures.book_online', 'departures.price_hide_show', 'departures.no_of_days', 'departures.no_of_nights', 'departures.slug_url_pre as slug1', 'departures.slug_url as slug2', 'departures.dep_dook_ref_id as slug3', 'departures.image', 'departures.created_at', 'departures.featured')
                     ->distinct()
                     ->orderBy('departures.featured', 'DESC');
       
               $query->with([
                   'hotelCategories' => function ($query) {
                       $query->select('departure_id', 'price_inr', 'price_usd')->orderBy('price_inr', 'ASC')->limit(1);
                   },
                   'inclusions' => function ($query) {
                       $query->whereNotNull('icon')->select('name', 'icon')->distinct();
                   }
               ]);
               return $query->paginate(6);
           });
          $destinations = Destination::where('country_id', '!=', 33)->get();

           foreach ($departures as $departure) {
               $departure->poi_names = Cache::remember("departure_{$departure->id}_poi", 60, function () use ($departure) {
                   return DepartureDestinationPointOfInterest::where('departure_id', $departure->id)
                       ->where('status', 1)
                       ->limit(4)
                       ->distinct()
                       ->pluck('poi_name')->toArray();
               });
       
               $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
               $departure->title = ucwords(strtolower($departure->title));
               $departure->dimage = $departure->image;
               $departure->image = $departure->image  ? generateSignedUrl('package/' . $departure->image) : url('assets/images') . '/maine-Image.jpg';
               $departure->featured = $departure->featured ? 'Best Selling' : '';
                 $departure->colMd = "col-md-4 col-12";

               if ($departure->hotelCategories->isNotEmpty()) {
                   $price = $departure->hotelCategories->first();
                   $departure->price = $price->price_inr;
                   $departure->price_usd = $price->price_usd;
               }
       
               $departure->inclusions = Cache::remember("departure_{$departure->id}_inclusions", 60, function () use ($departure) {
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
                   return $inclusions;
               });
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

           $departure_header = Cache::remember('departure_page_header', 60, function () {
               return DB::table('landing_departure_pages')
                   ->where('type', 'landing_departure')
                   ->select('title', 'sub_title', 'slug_url', 'banner_image', 'departure_recommend_description', 'meta_title', 'meta_keywords', 'meta_description', 'description')
                   ->first();
           });
            if ($departures->isEmpty()) {
            $noPackagesFoundMessage = "No packages found in the selected price range.";
            }

           return view('frontend.tours.international', compact('departure_header', 'departures', 'minPrice', 'maxPrice', 'flightFilter', 'selectedDestinations','destinations'));
    }
    public function domesticTourPackages(Request $request)
    {
            // Get maximum package price
             $maxPrice1 = Departure::join('departure_destinations', 'departure_destinations.departure_id', '=', 'departures.id')
                ->join('destinations', 'destinations.id', '=', 'departure_destinations.destination_id')
                ->join('country_departures', 'country_departures.departure_id', '=', 'departures.id')
                ->join('countries', 'countries.id', '=', 'country_departures.country_id')
                ->where('departures.status', 1)
                ->where('departures.dep_type', 'package')
                ->where('country_departures.country_id', '=', 33)
                ->max('departures.price') ?? 0;


            // Get min and max price filter
            $minPrice = $request->get('min_price', 0);
            $maxPrice = $request->get('max_price', $maxPrice1);

            // Get flight filter
            $flightFilter = $request->get('flight_filter');

            // Get selected destinations
            $selectedDestinations = array_filter(explode(',', $request->get('destinations', '')));

            // Get search keyword
            $keyword = $request->get('keyword', null);

            // Build the query
            $query = Departure::join('departure_destinations', 'departure_destinations.departure_id', '=', 'departures.id')
                ->join('destinations', 'destinations.id', '=', 'departure_destinations.destination_id')
                ->join('country_departures', 'country_departures.departure_id', '=', 'departures.id')
                ->where('country_departures.country_id', 33) // Domestic country ID
                ->where('departures.status', 1)
                ->where('departures.dep_type', 'package')
                ->when($request->has('min_price') || $request->has('max_price'), function ($q) use ($minPrice, $maxPrice) {
            $q->where(function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('departures.price', [$minPrice, $maxPrice])
                  ->orWhereNull('departures.price')
                  ->orWhere('departures.price', 0);
            });
        })
                ->select(['departures.id', 'departures.title', 'departures.price_currency', 'departures.price', 'departures.price_currency_usd', 'departures.price_usd', 'departures.book_online', 'departures.price_hide_show', 'departures.no_of_days', 'departures.no_of_nights', 'departures.slug_url_pre as slug1', 'departures.slug_url as slug2', 'departures.dep_dook_ref_id as slug3', 'departures.image', 'departures.created_at', 'departures.featured'])
                ->distinct()
                ->orderBy('departures.featured', 'DESC')
                 ->orderByRaw('CASE WHEN departures.price IS NULL OR departures.price = 0 THEN 1 ELSE 0 END ASC');

            // Apply keyword filter
            if (!is_null($keyword)) {
                $query->where('destinations.dest_name', 'LIKE', '%' . $keyword . '%');
            }

            // Apply flight filter
            if ($flightFilter === 'with_flight') {
                $query->whereExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('inclusions')
                        ->whereColumn('inclusions.departure_id', 'departures.id')
                        ->where('inclusions.name', 'Flights');
                });
            } elseif ($flightFilter === 'without_flight') {
                $query->whereNotExists(function ($subQuery) {
                    $subQuery->select(DB::raw(1))
                        ->from('inclusions')
                        ->whereColumn('inclusions.departure_id', 'departures.id')
                        ->where('inclusions.name', 'Flights');
                });
            }

            // Apply destination filter
            if (!empty($selectedDestinations)) {
                $query->whereIn('destinations.dest_name', $selectedDestinations);
            }

            // Get total count before pagination
            $totalTours = $query->count();

            // Fetch paginated results with relationships
            $departures = $query->with([
                'hotelCategories' => function ($query) {
                    $query->select('departure_id', 'price_inr', 'price_usd')->orderBy('price_inr', 'ASC')->limit(1);
                },
                'inclusions' => function ($query) {
                    $query->whereNotNull('icon')->select('name', 'icon')->distinct();
                }
            ])->paginate(6);
            $destinations = Destination::where('country_id', '=', 33)->get();
            // Process each departure
            foreach ($departures as $departure) {
                $departure->poi_names = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)
                    ->where('status', 1)
                    ->limit(4)
                    ->distinct()
                    ->pluck('poi_name')
                    ->toArray();

                $departure->title = ucwords(strtolower($departure->title));
                $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
                $departure->dimage = $departure->image;
                $departure->image = $departure->image ? generateSignedUrl('package/' . $departure->image) : url('assets/images') . '/maine-Image.jpg';
                $departure->featured = $departure->featured ? 'Best Selling' : '';
                $departure->colMd = "col-md-4 col-12";
                // Set price from hotel categories if available
                if ($departure->hotelCategories->isNotEmpty()) {
                    $price = $departure->hotelCategories->first();
                    $departure->price = $price->price_inr;
                    $departure->price_usd = $price->price_usd;
                }

                // Fetch inclusions with caching
                $departure->inclusions = Cache::remember("departure_{$departure->id}_inclusions", 60, function () use ($departure) {
                   return DB::table('inclusions')
                    ->join('inclusion_masters', 'inclusions.icon_inclusion_id', '=', 'inclusion_masters.id')
                    ->where('inclusions.departure_id', $departure->id)
                    ->whereNotNull('inclusions.icon_inclusion_id')
                    ->select('inclusion_masters.name', 'inclusion_masters.icon')
                    ->distinct()
                    ->get()
                    ->map(function ($inclusion) {
                        $inclusion->icon = generateSignedUrl("inclusion/" . $inclusion->icon);
                        return $inclusion;
                    });

                });
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

            // Handle AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                    'hasMorePages' => $departures->hasMorePages()
                ]);
            }

            // Fetch departure page header with caching
            $departure_header = Cache::remember('departure_page_header_domestic', 60, function () {
                return DB::table('landing_departure_pages')
                    ->where('type', 'landing_domestic')
                    ->select('title', 'sub_title', 'slug_url', 'banner_image', 'departure_recommend_description', 'meta_title', 'meta_keywords', 'meta_description', 'description')
                    ->first();
            });
            if ($departures->isEmpty()) {
            // Set a message or adjust the filter to notify that no packages are found
            $noPackagesFoundMessage = "No packages found in the selected price range.";
            }
            return view('frontend.tours.domestic_tour_packages', compact('departure_header','departures', 'minPrice','maxPrice','flightFilter','selectedDestinations','destinations'));
    }
    public function packageDetails(Request $request, $country, $slug, $dook_ref_id){ 
        $departure = Departure::where(['slug_url_pre' => $country, 'slug_url' => $slug, 'dep_dook_ref_id' => $dook_ref_id])->first();     
        if (!$departure) {
            return redirect('/');
        }  
        if ($departure->status == 0) {
        abort(404); // or redirect
        }
        $departure->price_with_profit = $departure->price * 1.1;  
        $departure->meta_title = $departure->meta_title ?: $departure->title;
        $departure->title = ucwords(strtolower($departure->title));
        $poi = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)
                    ->where('status', 1)
                    ->distinct()
                    ->get(['reference_id as poiId', 'poi_name', 'image', 'latitude', 'longitude', 'description'])
                    // One entry per attraction - duplicate link rows differ by
                    // image, so SELECT DISTINCT does not collapse them.
                    ->unique('poiId')
                    ->values();
            // Counted for the whole set at once (cached per POI) instead of
            // two queries per POI inside the map below.
            $poiCounts = poiDepartureCounts($poi->pluck('poiId')->all());

              $departure->poi = $poi->map(function ($value) use ($poiCounts) {

            // Image
            $value->image = $value->image
                ? generateSignedUrl('poi/' . $value->image)
                : url('images') . '/assets/no-image.jpg';

            // URL
            $value->poi_url = Str::slug($value->poi_name);

            // TOTAL ACTIVE DEPARTURES / FEATURED PACKAGE DEPARTURES
            $value->total_departures = $poiCounts[$value->poiId]['total'] ?? 0;
            $value->featured_departure = $poiCounts[$value->poiId]['featured'] ?? 0;

            return $value;
        });

        $departure->poiTotal = $poi->count();

        $destinationIds = DepartureDestination::where('departure_id', $departure->id)
                                            ->distinct()
                                            ->pluck('destination_id');
        $departure->destinations = DB::table('destinations')
                                    ->whereIn('id', $destinationIds)
                                    ->select('id', 'latitude', 'longitude', 'dest_name', 'image', 'country_name as country', 'slug_url')
                                    ->orderByRaw('FIELD(id, ' . implode(',', $destinationIds->toArray()) . ')')
                                    ->get()
                                    ->map(function($destination) {
                                        $destination->image = $destination->image ? generateSignedUrl('poi/' . $destination->image) : url('images') . '/poi-no-image.jpg';
                                        return $destination;
                                    });
        $departure->destinationTotal = $destinationIds->count();
        $departure_images = DB::table('departure_images')
                            ->where('departure_id', $departure->id)
                            ->pluck('image')
                            ->map(function($image) {
                                return ['image' => generateSignedUrl('package/' . $image)];
                            });        
        $departure->images = $departure_images;
        $activityIds = DB::table('activity_departures')
                        ->where('departure_id', $departure->id)
                        ->distinct()
                        ->pluck('activity_id');
        $departure->activities = DB::table('activities')
                                    ->whereIn('id', $activityIds)
                                    ->where('status', 1)
                                    ->select('id', 'activity_name', 'slug_url', 'image')
                                    ->get()
                                    ->map(function($activity) {
                                        $activity->image = $activity->image ? generateSignedUrl('activities/' . $activity->image) : url('images') . '/event-no-image.jpg';
                                        return $activity;
                                    });
        $itinerary = Itinerary::where('departure_id', $departure->id)->get();
        $itinerary->each(function($item) {
            $item->included = Inclusion::whereIn('id', explode(',', $item->included))->pluck('name');
            $item->excluded = Exclusion::whereIn('id', explode(',', $item->excluded))->pluck('name');
            $item->destinations = DB::table('destination_itinerary_point_of_interests')
                                    ->where('itinerary_id', $item->id)
                                    ->pluck('destination_id')
                                    ->map(function($destId) {
                                        return DB::table('destinations')
                                                ->where('id', $destId)
                                                ->select('dest_name')
                                                ->first();
                                    });
        });
        $departure->itinerary = $itinerary;
        $allExclusions = $itinerary->flatMap(function($item) {
            return $item->excluded; 
        })->unique();  
        
        $departure->uniqueExclusions = $allExclusions;
        $beforeYouGoData = DB::table('before_yougo_country_departures')
                            ->where('departure_id', $departure->id)
                            ->distinct()
                            ->pluck('country_id')
                            ->map(function($countryId) {
                                return DB::table('countries')
                                        ->where('id', $countryId)
                                        ->select('country_name as countryName', 'before_you_go as description')
                                        ->first();
                            });

        $departure->beforeYouGoData = $beforeYouGoData;
        $inclusions = Inclusion::where('departure_id', $departure->id)
        ->select('name') 
        ->get();

        $departure->inclusions = $inclusions;
        $locationDataT = [];
        $ph_country = "IN";        
         $vCountry_id = DB::table('country_departures')
         ->where('departure_id',$departure->id)
         ->pluck('country_id')
         ->toArray();
        $vCountry = DB::table('countries')
                    ->where('status',1)
                    ->whereIn('id',$vCountry_id)
                    ->distinct()
                    ->pluck('iso_2')
                    ->toArray();
        if($ph_country != ''){
            $post = array(
                'phCountryIso2' => $ph_country,
                'pkg_countries' => $vCountry
            );
        }
        else{
            $post = array(
                'phCountryIso2' => 'IN',
                'pkg_countries' => $vCountry
            );
        }
        $data_string = json_encode($post);
        $data_object = json_decode($data_string);  

        $ph_iso2 = $data_object->phCountryIso2;
        $pkg_iso2 = $data_object->pkg_countries;
            $data = Visa::where(['residence_iso2'=> $ph_iso2, 'status' => 1])
            ->whereIn('visiting_iso2', $pkg_iso2)
            ->select('id','passport_holder_country as phCountry','residence_iso2 as phCountryIso2','visiting_country','visiting_iso2 as vCountryIso2','slug_url as url','ph_country_url','v_country_url')
            ->distinct()
            ->get()->toArray();
            $uniqueData = [];
            foreach ($data as $item) {
                $key = $item['phCountryIso2'] . '-' . $item['vCountryIso2'];
                if (!isset($uniqueData[$key])) {
                    $uniqueData[$key] = $item;
                }
            }
            $data = array_values($uniqueData);
            foreach ($data as &$vdt) {
                $vdt['source_destination'] = "";
            }

            unset($vdt);
        $visas = json_decode(json_encode($data));
        if($visas == null){
            $departure['visa'] = [];
        }
        else{
            $departure['visa'] = $visas;
        }
        return view('frontend.tours.package_detail', compact('departure'));
    }

    public function grouppack(Request $request)
    {
         $selectedDestinations = collect(explode(',', $request->get('destinations', '')))
            ->map(fn($d) => urldecode(trim($d)))
            ->filter()
            ->toArray();

        $departure_header = DB::table('landing_departure_pages')
            ->where('type', 'landing_group_tours')
            ->select('title', 'sub_title', 'slug_url', 'banner_image', 'departure_recommend_description', 'meta_title', 'meta_keywords', 'meta_description', 'description')
            ->first();

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
                    $res = $response->getBody()->getContents();
                    return json_decode($res, true);
                } else {
                    Log::error("API request failed", ['url' => $apiUrl, 'status' => $response->status()]);
                    return null;
                }
            });

            $departures = $toursResponse !== null ? $toursResponse['Result'] : null;

        } catch (Exception $e) {
            Log::error("API Error", ['message' => $e->getMessage(), 'Api Url' => $apiUrl]);
            $departures = null;
        }

        $iconInclusions = IconInclusion::all()->keyBy('name');
        $departures = collect($departures)->map(function ($tour) use ($iconInclusions) {
            $poi_names = [];
            $tour['Itinerary'] = array_map(function ($item) use (&$poi_names) {
                $poi = $item['Attraction'] ?? [];
                $poi_names = array_merge($poi_names, $poi);
                return (object) [
                    'poi' => $poi,
                ];
            }, $tour['Itinerary']);

            $tour['poi'] = array_map(function ($poiItem) {
                return $poiItem['Name'];
            }, array_slice(array_unique($poi_names, SORT_REGULAR), 0, 4)); 

            $inclusions = $tour['DepartureDateWithPrice'][0]['Inclusion'] ?? [];

            $mappedInclusions = array_map(function ($offer) use ($iconInclusions) {
                $icon = isset($iconInclusions[$offer]) ? generateSignedUrl("inclusion/" . $iconInclusions[$offer]->icon) : null;
                return (object) [
                    'name' => $offer,
                    'icon' => $icon,
                ];
            }, $inclusions);

            // Collect all unique destinations from the tour
            $tour['destinations'] = $tour['Destination'] ?? [];

            return (object) [
                'slug1' => 'group-tours',
                'slug2' => $tour['DookSlug'],
                'slug3' => $tour['DookDepartureId'],
                'image' => $tour['DookImage'][0] ?? asset('assets/images/maine-Image.jpg'),
                'title' => strtok($tour['Name'], '-'),
                'featured' => $tour['BestSellingPackage'], // Assuming this field determines the best-selling status
                'price' => $tour['MinimumPublishedPrice'] ?? $tour['Price'] ?? null,
                'no_of_nights' => $tour['DayNight'],
                'poi_names' => $tour['poi'],
                'inclusions' => $mappedInclusions,
                'destinations' => $tour['destinations'],  // Add destinations to the tour data
                'colMd' => 'col-md-4 col-12',
                'offers' => $tour['Offers'],
                'country_name' => $tour['Country'][0] ?? null,
            ];
        });

       // destination filter
        $destinations = $departures->flatMap(fn($tour) => $tour->destinations)
            ->map(fn($d) => trim($d))
            ->unique()
            ->values();

        if (!empty($selectedDestinations)) {
            $departures = $departures->filter(function ($tour) use ($selectedDestinations) {
                $tourDestinations = collect($tour->destinations)
                    ->map(fn($d) => strtolower(trim($d)));
                $selected = collect($selectedDestinations)
                    ->map(fn($d) => strtolower(trim($d)));
                return $tourDestinations->intersect($selected)->isNotEmpty();
            });
        }

        // Price filter
        $minPrice = $departures->min('price') ?? 0;
        $maxPrice1 = $departures->max('price') ?? 0;
        $minFilter = $request->get('min_price', $minPrice);
        $maxFilter = $request->get('max_price', $maxPrice1);
        $departures = $departures->filter(fn($tour) => $tour->price >= $minFilter && $tour->price <= $maxFilter);

        // Flight filter
        $flightFilter = $request->input('flight_filter', 'both');
        $departures = $departures->filter(function ($tour) use ($flightFilter) {
            $hasFlight = in_array('Flight', $tour->offers);
            return match ($flightFilter) {
                'with_flight' => $hasFlight,
                'without_flight' => !$hasFlight,
                default => true,
            };
        });

        $departures = $departures->sortByDesc('featured')->values();
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

         $countries = DB::table('countries')
                ->where('status',1)
                ->whereNotNull('country_group_slug_url')
                  ->select('id','country_name as countryName','slug_url','image','about_country_slug_url','country_attraction_slug_url','country_group_slug_url')
                ->where('status',1)
                ->paginate(8);

        if(count($countries)>0){
            foreach ($countries as $key => $value) {
                if($value->image != "" || $value->image != null){
                    $value->image = generateSignedUrl('country/'.$value->image);
                }else{
                    $value->image = url('images').'/poi-no-image.jpg';
                }
                $value->about_country_slug_url = "";
                $value->country_attraction_slug_url = "";
                $value->colMd = "col-md-3 col-lg-3 col-sm-4 col-xs-12 col-6";
                $value->cType = 'country_group';
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                'hasMorePages' => $departures->hasMorePages(),
                'countries' => view('frontend.countries.countries_card', compact('countries'))->render(),
                 'hasMoreCountries' => $countries->hasMorePages(),
            ]);
        }

        return view('frontend.tours.group_tour', compact('departure_header', 'departures', 'maxPrice1', 'destinations','selectedDestinations','countries','minPrice'));
    }

    public function agentdeparture(Request $request, $slug, $id)
    { 
        $header = [
            "Username" => env('AGENT_CONNECT_USERNAME'),
           "Password" => env('AGENT_CONNECT_PASSWORD')
        ];
        $apiUrl = env('AGENT_CONNECT_API_BASE_URL')."/departure/group-departure";

        try {
            $toursResponse = Cache::remember('group-tours', 300, function () use ($apiUrl, $header) {
                $response = Http::retry(3, 100)
                                ->withHeaders($header)
                                ->get($apiUrl);
                if ($response->successful()) {
                    $res = $response->getBody()->getContents();
                    return json_decode($res, true);
                } else {
                    Log::error("API request failed", ['url' => $apiUrl, 'status' => $response->status()]);
                    return null;
                }
            });
            $departures = $toursResponse !== null ? $toursResponse['Result'] : null; 
           $responseData = $toursResponse['Result']; 
            $departuresArray = json_decode(json_encode($responseData), true);

            $departuresIndexed = array_values($departuresArray);
              
            $toursResponse1 = array_filter($departuresIndexed, function ($dep) use ($id) {
                return $dep['DookDepartureId'] == $id;
            });
             $toursResponse1 = array_values($toursResponse1);

        $toursResponse1 = reset($toursResponse1);

        } catch (Exception $e) {
            Log::error("API Error", ['message' => $e->getMessage(), 'Api Url' => $apiUrl]);
            $departures = null; 
        }                

        $departure = collect($departures)->filter(function ($tour) use ($id) {             
            return $tour['DookDepartureId'] == $id;         
        })->map(function ($tour) {     
            // For Visa
            $policy = DB::table('term_and_conditions')->where(['type' => 'group-tours'])->select('conditions')->first();  
            $ph_country = "IN";   
            $vCountry_id = DB::table('countries')
                ->where('country_name', $tour['Country'][0])
                ->pluck('id')
                ->toArray();     
            $vCountry = DB::table('countries')
                ->where('status', 1)
                ->whereIn('id', $vCountry_id)
                ->distinct()
                ->pluck('iso_2')
                ->toArray();

            $post = [
                'phCountryIso2' => $ph_country != '' ? $ph_country : 'IN',
                'pkg_countries' => $vCountry
            ];

            $ph_iso2 = $post['phCountryIso2'];
            $pkg_iso2 = $post['pkg_countries'];

            $visas = Visa::where(['residence_iso2' => $ph_iso2, 'status' => 1])
                ->whereIn('visiting_iso2', $pkg_iso2)
                ->select('id', 'passport_holder_country as phCountry', 'residence_iso2 as phCountryIso2', 'visiting_country', 'visiting_iso2 as vCountryIso2', 'slug_url as url', 'ph_country_url', 'v_country_url')
                ->distinct()
                ->get()
                ->toArray();
                
            $uniqueVisas = [];
            foreach ($visas as $visa) {
                $key = $visa['phCountry'] . '-' . $visa['vCountryIso2']; 

                if (!isset($uniqueVisas[$key])) {
                    $uniqueVisas[$key] = $visa;
                }
            }
            $uniqueVisas = array_values($uniqueVisas);
            foreach ($uniqueVisas as &$vdt) {
                $vdt['source_destination'] = "";
            }
            $tour['visa'] = !empty($uniqueVisas) ? $uniqueVisas : [];


            // $post = [
            //     'phCountryIso2' => $ph_country != '' ? $ph_country : 'IN',
            //     'pkg_countries' => $vCountry
            // ];
            // $ph_iso2 = $post['phCountryIso2'];
            // $pkg_iso2 = $post['pkg_countries'];

            // $visas = Visa::where(['residence_iso2'=> $ph_iso2, 'status' => 1])
            //         ->whereIn('visiting_iso2', $pkg_iso2)
            //         ->select('id','passport_holder_country as phCountry','residence_iso2 as phCountryIso2','visiting_country','visiting_iso2 as vCountryIso2','slug_url as url','ph_country_url','v_country_url')
            //         ->distinct()
            //         ->get()->toArray();
            // $visas = array_unique($visas);
            // foreach($visas as $vdt) {
            //     $vdt['source_destination'] = "";
            // } 
            // if ($visas == null) {
            //     $tour['visa'] = [];
            // } else {
            //     $tour['visa'] = $visas;
            // }

            $allPoi = [];

            $tour['Itinerary'] = array_map(function($item) use (&$allPoi) {
                $poi = $item['Attraction'] ?? []; 
                $allPoi = array_merge($allPoi, $poi); 

                return (object) [
                    'id' => $item['Id'],
                    'day_number' => $item['Day'],
                    'city' => $item['City'],
                    'day_heading' => $item['Title'],
                    'description' => $item['Description'],
                    'flight' => $item['Flight'],
                    'hotel' => $item['Hotel'],
                    'poi' => $poi,
                ];
            }, $tour['Itinerary']);


            $tour['poi'] = array_map(function ($poiItem) {
                return [
                    'poiId' => $poiItem['AttractionId'],  
                    'poi_slug' => Null,
                    'poi_name' => $poiItem['Name'],
                    'image' => $poiItem['Image'],
                    'duration' => $poiItem['Duration'],
                ];
            }, array_unique($allPoi, SORT_REGULAR));

             $destinationNames = $tour['Destination'] ?? []; 
            $tour['Destination'] = [];
            if (!empty($destinationNames)) {
                $dbDestinations = DB::table('destinations')
                    ->whereIn('dest_name', $destinationNames)
                    ->select('dest_name', 'slug_url')
                    ->get()
                    ->toArray(); 
                $matchedNames = array_column($dbDestinations, null, 'dest_name');
                foreach ($destinationNames as $destName) {
                    if (isset($matchedNames[$destName])) {
                        $tour['Destination'][] = [
                            'dest_name' => $matchedNames[$destName]->dest_name,
                            'slug_url' => $matchedNames[$destName]->slug_url,
                        ];
                    } else {
                        $tour['Destination'][] = [
                            'dest_name' => $destName,
                            'slug_url' => null,
                        ];
                    }
                }
            }
            // departuredates
            
            $departureDates = [];
            if (!empty($tour['DepartureDateWithPrice'])) {
                foreach ($tour['DepartureDateWithPrice'] as $dateInfo) {
                    if (!empty($dateInfo['DepartureDate']) && !empty($dateInfo['FareInfo'])) {
                        foreach ($dateInfo['FareInfo'] as $room) {
                            if ($room['RoomShare'] === 'Double') {
                                // Correcting the date format to 'd-m-Y'
                                $formattedDate = \Carbon\Carbon::parse($dateInfo['DepartureDate'])->format('d-m-Y');

                                // Check if AvailableSeats is greater than zero
                                // if (!empty($dateInfo['AvailableSeats']) && $dateInfo['AvailableSeats'] > 0) {
                                //     // Append an asterisk to the formatted date
                                //     $formattedDate .= ' *';
                                // }

                                $departureDates[$formattedDate] = [
                                    'price' => $room['OfferedPrice'] ?? 0,
                                    'inclusions' => $dateInfo['Inclusion'] ?? [],
                                ];
                                break;
                            }
                        }
                    }
                }
            }
            return (object) [
                'dep_dook_ref_id' => $tour['DookDepartureId'],
                'slug1' => 'group-tours',
                'slug2' => $tour['DookSlug'],
                'slug3' => $tour['DookDepartureId'],
                'description' => $tour['Description'],
                'image' => $tour['DookImage'][0],
                'title' => strtok($tour['Name'], '-'),
                'featured' => $tour['BestSellingPackage'],
                'price' => $tour['MinimumPublishedPrice'] ?? $tour['Price'] ?? null,
                'no_of_nights' => $tour['DayNight'],
                'meta_title' => $tour['SeoDetails']['MetaTitle'] ?? null,
                'meta_keywords' => $tour['SeoDetails']['MetaKeyword'] ?? null,
                'meta_description' => $tour['SeoDetails']['MetaDescription'] ?? null,
                'inclusions' => $tour['DepartureDateWithPrice'][0]['Inclusion'] ?? null,
                'visa' => $tour['visa'],
                'conditions' => $policy,
                'dep_type' => 'main',
                'destinationTotal' => count($tour['Destination']),
                'poiTotal' => (
                    isset($tour['DepartureDateWithPrice'][0]['Inclusion'])
                        ? count($tour['DepartureDateWithPrice'][0]['Inclusion'])
                        : 0
                ),
                'from' => $tour['StartingFrom'],
                'ending_at' => $tour['EndingAt'],
                'description' => $tour['Description'],
                'uniqueExclusions' => $tour['Exclusion'],
                'itinerary' => $tour['Itinerary'], 
                'poi' => $tour['poi'],
                'conditions' =>$policy,
                'gallery' => $tour['Gallery'],
                'visa' => $tour['visa'],
                'departure_dates' => $departureDates,
                'departure_price_seats' => $tour['DepartureDateWithPrice'],
                  'Destination' => $tour['Destination'],
                
            ];
        })->first();
        if(!$departure){
            ////return view('errors.');
            return redirect('/');
        }
        return view('frontend.tours.agent_package_detail', compact('departure','toursResponse1'));
    }
}
