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
        $keyword = request()->get('keyword', null); 
        $cacheKey = 'international_tour_packages_' . md5($keyword . request()->get('page', 1));
        $departures = Cache::remember($cacheKey, 60, function() use ($keyword) {
            $query = Departure::join('departure_destinations', 'departure_destinations.departure_id', '=', 'departures.id')
                ->join('destinations', 'destinations.id', '=', 'departure_destinations.destination_id')
                ->join('country_departures', 'country_departures.departure_id', '=', 'departures.id')
                ->join('countries', 'countries.id', '=', 'country_departures.country_id')
                ->where('departures.status', 1)
                ->where('departures.dep_type', 'package')
                ->where('country_departures.country_id', '!=', 33)
                ->select('departures.id', 'departures.title', 'departures.price_currency', 'departures.price', 'departures.price_currency_usd', 'departures.price_usd', 'departures.book_online', 'departures.price_hide_show', 'departures.no_of_days', 'departures.no_of_nights', 'departures.slug_url_pre as slug1', 'departures.slug_url as slug2', 'departures.dep_dook_ref_id as slug3', 'departures.image', 'departures.created_at', 'departures.featured')
                ->distinct('departures.created_at')
                ->orderBy('departures.featured', 'DESC');

            if (!is_null($keyword)) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('destinations.dest_name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('countries.country_name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('countries.continent', 'LIKE', '%' . $keyword . '%');
                });
            }

            return $query->with(['hotelCategories' => function($query) {
                $query->select('departure_id', 'price_inr', 'price_usd')->orderBy('price_inr', 'ASC')->limit(1); 
            }, 'inclusions' => function($query) {
                $query->whereNotNull('icon')->select('name', 'icon')->distinct();
            }])->paginate(6);
        });

        // $totalTours = $departures->count();

        foreach ($departures as $departure) {
            $departure->poi_names = Cache::remember("departure_{$departure->id}_poi", 60, function() use ($departure) {
                return DepartureDestinationPointOfInterest::where('departure_id', $departure->id)
                    ->where('status', 1)
                    ->limit(4)
                    ->distinct()
                    ->pluck('poi_name')->toArray();
            });
            $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
            $departure->title = ucwords(strtolower($departure->title));
            $departure->dimage = $departure->image;
            $departure->image = $departure->image ? env('AWS_BUCKET_URL') . '/package/' . $departure->image : url('images') . '/package-no-image.jpg';
            $departure->featured = $departure->featured ? 'Best Selling' : ''; 

            if ($departure->hotelCategories->isNotEmpty()) {
                $price = $departure->hotelCategories->first();
                $departure->price = $price->price_inr;
                $departure->price_usd = $price->price_usd;
            }

            $departure->inclusions = Cache::remember("departure_{$departure->id}_inclusions", 60, function() use ($departure) {
                $inclusions = DB::table('inclusions')
                    ->where('departure_id', $departure->id)
                    ->whereNotNull('icon')
                    ->select('name', 'icon')
                    ->distinct()
                    ->get();
                foreach ($inclusions as $inclusion) {
                    $inclusion->icon = env('AWS_BUCKET_URL') . "/inclusion/" . $inclusion->icon;
                }
                return $inclusions;
            });
        }
       if ($request->ajax()) {
            return response()->json([
                'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                'hasMorePages' => $departures->hasMorePages()
            ]);
        }
        $departure_header = Cache::remember('departure_page_header', 60, function() {
            return DB::table('landing_departure_pages')
                ->where('type', 'landing_departure')
                ->select('title', 'sub_title', 'slug_url', 'banner_image', 'departure_recommend_description', 'meta_title', 'meta_keywords', 'meta_description', 'description')
                ->first();
        });


        return view('frontend.tours.international', compact('departure_header', 'departures'));
    }


    public function domesticTourPackages(Request $request)
    {
        $keyword = request()->get('keyword', null); 
        $query = Departure::join('departure_destinations', 'departure_destinations.departure_id', '=', 'departures.id')
        ->join('destinations', 'destinations.id', '=', 'departure_destinations.destination_id')
        ->join('country_departures', 'country_departures.departure_id', '=', 'departures.id')
        ->where('country_departures.country_id', 33)
        ->where('departures.status', 1)
        ->where('departures.dep_type', 'package')
        ->select('departures.id', 'departures.title', 'departures.price_currency', 'departures.price', 'departures.price_currency_usd', 'departures.price_usd', 'departures.book_online', 'departures.price_hide_show', 'departures.no_of_days', 'departures.no_of_nights', 'departures.slug_url_pre as slug1', 'departures.slug_url as slug2', 'departures.dep_dook_ref_id as slug3', 'departures.image', 'departures.created_at', 'departures.featured')
        ->distinct('departures.created_at')
        ->orderBy('departures.featured', 'DESC');

        if (!is_null($keyword)) {
            $query->where('destinations.dest_name', 'LIKE', '%' . $keyword . '%');
        }

        $totalTours = $query->count(); 
        $departures = $query->with(['hotelCategories' => function($query) {
            $query->select('departure_id', 'price_inr', 'price_usd')->orderBy('price_inr', 'ASC')->limit(1); 
        }, 'inclusions' => function($query) {
            $query->whereNotNull('icon')->select('name', 'icon')->distinct();
        }])->paginate(6); 
        foreach ($departures as $departure) {          
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
            $departure->poi_names = $poiNames;
            $departure->title = ucwords(strtolower($departure->title));
            $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
            $departure->dimage = $departure->image;
            $departure->image = $departure->image ? env('AWS_BUCKET_URL') . '/package/' . $departure->image : url('images') . '/package-no-image.jpg';
            $departure->featured = $departure->featured ? 'Best Selling' : ''; 
   
            if ($departure->hotelCategories->isNotEmpty()) {
                $price = $departure->hotelCategories->first();
                $departure->price = $price->price_inr;
                $departure->price_usd = $price->price_usd;
            }
            $inclusions = DB::table('inclusions')
            ->where('departure_id', $departure->id)
            ->whereNotNull('icon')
            ->select('name', 'icon')
            ->distinct()
            ->get();
            foreach ($inclusions as $inclusion) {
                $inclusion->icon = env('AWS_BUCKET_URL') . "/inclusion/" . $inclusion->icon;
            }
            $departure->inclusions = $inclusions;           
        }
        if ($request->ajax()) {
            return response()->json([
                'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                'hasMorePages' => $departures->hasMorePages()
            ]);
        }
        $departure_header = DB::table('landing_departure_pages')
            ->where('type', 'landing_domestic')
            ->select('title', 'sub_title', 'slug_url', 'banner_image', 'departure_recommend_description', 'meta_title', 'meta_keywords', 'meta_description', 'description')
            ->first(); 
        return view('frontend.tours.domestic_tour_packages', compact('departure_header', 'departures','totalTours'));

    }
    public function packageDetails(Request $request, $country, $slug, $dook_ref_id){ 
        $departure = Departure::where(['slug_url_pre' => $country, 'slug_url' => $slug, 'dep_dook_ref_id' => $dook_ref_id])->first();     
        if (!$departure) {
            return redirect('/404');
        }  
        $departure->meta_title = $departure->meta_title ?: $departure->title;
        $departure->title = ucwords(strtolower($departure->title));
        $poi = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)
                    ->where('status', 1)
                    ->distinct()
                    ->get(['reference_id as poiId', 'poi_name', 'image', 'latitude', 'longitude', 'description']);        
        $departure->poi = $poi->map(function($value) {
            $value->image = $value->image ? env('AWS_BUCKET_URL') . '/poi/' . $value->image : url('images') . '/poi-no-image.jpg';            
            $value->poi_url = Str::slug($value->poi_name);
            $total_departure = DB::table('departure_destination_point_of_interests')
                                    ->where('reference_id', $value->poiId)
                                    ->pluck('departure_id');            
            $featured_departure = DB::table('departures')
                                    ->whereIn('id', $total_departure)
                                    ->where('featured', 1)
                                    ->count();            
            $value->total_departures = $total_departure->count();
            $value->featured_departure = $featured_departure;            
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
                                        $destination->image = $destination->image ? env('AWS_BUCKET_URL') . '/poi/' . $destination->image : url('images') . '/poi-no-image.jpg';
                                        return $destination;
                                    });
        $departure->destinationTotal = $destinationIds->count();
        $departure_images = DB::table('departure_images')
                            ->where('departure_id', $departure->id)
                            ->pluck('image')
                            ->map(function($image) {
                                return ['image' => env('AWS_BUCKET_URL') . '/package/' . $image];
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
                                        $activity->image = $activity->image ? env('AWS_BUCKET_URL') . '/activities/' . $activity->image : url('images') . '/event-no-image.jpg';
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
        $departure_price = DB::table('hotel_categories')
                            ->where('departure_id', $departure->id)
                            ->distinct()
                            ->orderBy('price_inr', 'DESC')
                            ->first(['price_inr', 'price_usd']);
        
        $departure->price = $departure_price->price_inr ?? 0;
        $departure->price_usd = $departure_price->price_usd ?? 0;   
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
        $departure_header = DB::table('landing_departure_pages')
            ->where('type', 'landing_group_tours')
            ->select('title', 'sub_title', 'slug_url', 'banner_image', 'departure_recommend_description', 'meta_title', 'meta_keywords', 'meta_description', 'description')
            ->first();

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

        } catch (Exception $e) {
            Log::error("API Error", ['message' => $e->getMessage(), 'Api Url' => $apiUrl]);
            $departures = null; 
        }

        $iconInclusions = IconInclusion::all()->keyBy('name');
        $departures = collect($departures)->map(function ($tour) use ($iconInclusions) {
            $poi_names = [];
            $tour['Itinerary'] = array_map(function($item) use (&$poi_names) {
                $poi = $item['Attraction'] ?? []; 
                $poi_names = array_merge($poi_names, $poi); 
                return (object) [
                    'poi' => $poi,
                ];
            }, $tour['Itinerary']);

            $tour['poi'] = array_map(function ($poiItem) {
                return $poiItem['Name'];
            }, array_unique($poi_names, SORT_REGULAR));

            $inclusions = $tour['DepartureDateWithPrice'][0]['Inclusion'];

            $mappedInclusions = array_map(function ($offer) use ($iconInclusions) {
                $icon = isset($iconInclusions[$offer]) ? env('AWS_BUCKET_URL') . "/inclusion/" . $iconInclusions[$offer]->icon : null;
                return (object) [
                    'name' => $offer,
                    'icon' => $icon,
                ];
            }, $inclusions);

            return (object) [
                'slug1' => 'group-tours',     
                'slug2' => $tour['DookSlug'],   
                'slug3' => $tour['DookDepartureId'],     
                'image' => $tour['DookImage'][1],         
                'title' => strtok($tour['Name'], '-'),             
                'featured' => $tour['BestSellingPackage'], 
                'price' => $tour['MinimumPublishedPrice'],         
                'no_of_nights' => $tour['DayNight'],    
                'poi_names' => $tour['poi'],
                'inclusions' => $mappedInclusions,
            ];
        });

        $departures = $departures->forPage($request->page ?? 1, 10); 
        $departures = new \Illuminate\Pagination\LengthAwarePaginator($departures, count($departures), 10, $request->page ?? 1);

        if ($request->ajax()) {
            return response()->json([
                'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                'hasMorePages' => $departures->hasMorePages()
            ]);
        }

        return view('frontend.tours.group_tour', compact('departure_header', 'departures'));
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

            $visas = Visa::where(['residence_iso2'=> $ph_iso2, 'status' => 1])
                    ->whereIn('visiting_iso2', $pkg_iso2)
                    ->select('id','passport_holder_country as phCountry','residence_iso2 as phCountryIso2','visiting_country','visiting_iso2 as vCountryIso2','slug_url as url','ph_country_url','v_country_url')
                    ->distinct()
                    ->get()->toArray();
            $visas = array_unique($visas);
            foreach($visas as $vdt) {
                $vdt['source_destination'] = "";
            } 
            if ($visas == null) {
                $tour['visa'] = [];
            } else {
                $tour['visa'] = $visas;
            }

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

            return (object) [
                'dep_dook_ref_id' => $tour['DookDepartureId'],
                'slug1' => 'group-tours',
                'slug2' => $tour['DookSlug'],
                'slug3' => $tour['DookDepartureId'],
                'description' => $tour['Description'],
                'image' => $tour['DookImage'][1],
                'title' => strtok($tour['Name'], '-'),
                'featured' => $tour['BestSellingPackage'],
                'price' => $tour['MinimumPublishedPrice'],
                'no_of_nights' => $tour['DayNight'],
                'meta_title' => $tour['SeoDetails']['MetaTitle'] ?? null,
                'meta_keywords' => $tour['SeoDetails']['MetaKeyword'] ?? null,
                'meta_description' => $tour['SeoDetails']['MetaDescription'] ?? null,
                'inclusions' => $tour['DepartureDateWithPrice'][0]['Inclusion'] ?? null,
                'visa' => $tour['visa'],
                'conditions' => $policy,
                'dep_type' => 'main',
                'destinationTotal' => count($tour['Destination']),
                'poiTotal' => count($tour['DepartureDateWithPrice'][0]['Inclusion']),
                'from' => $tour['StartingFrom'],
                'ending_at' => $tour['EndingAt'],
                'description' => $tour['Description'],
                'uniqueExclusions' => $tour['Exclusion'],
                'itinerary' => $tour['Itinerary'], 
                'poi' => $tour['poi'],
                'conditions' =>$policy,
                'gallery' => $tour['Gallery'],
                'visa' => $tour['visa'],
                
            ];
        })->first();

        return view('frontend.tours.agent_package_detail', compact('departure'));
    }
}
