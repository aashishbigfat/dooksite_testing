<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departure;
use App\Models\Country;
use App\Models\Destination;
use App\Models\CountryDeparture;
use App\Models\DepartureDestination;
use App\Models\Experience;
use App\Models\DestinationExperience;
use App\Models\ActivityPointOfInterest;
use App\Models\Activity;
use App\Models\DepartureDestinationPointOfInterest;
use DB;
use Illuminate\Support\Arr;
use App\Models\ActivityDeparture;

class CommonSearchController extends Controller
{
    public function commonSearch(Request $request)
    {
        $keyword = $request->searchKeyword;
        $packages = array();
        $countries = array();
        $destinations = array();
        
        if($keyword){
            // Extract meaningful search terms (remove stop words, conjunctions)
            $searchTerms = $this->extractMeaningfulTerms($keyword);
            $fuzzyPatterns = $this->generateMultipleFuzzyPatterns($searchTerms);
            
            // ==================== PACKAGES SEARCH ====================
            
            // Query 1: Direct departure search with natural language support
            $departures1 = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        ->where('departures.status', 1)
                        ->where('departures.dep_type','=','package')
                        ->where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns){
                            // Original exact/partial match with full keyword
                            $query->where('departures.title', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('departures.slug_url_pre', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('departures.dep_dook_ref_id', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('destinations.dest_name', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('countries.country_name', 'LIKE', '%'.$keyword.'%');
                            
                            // Individual meaningful terms
                            foreach($searchTerms as $term) {
                                $query->orWhere('departures.title', 'LIKE', '%'.$term.'%')
                                    ->orWhere('departures.slug_url_pre', 'LIKE', '%'.$term.'%')
                                    ->orWhere('destinations.dest_name', 'LIKE', '%'.$term.'%')
                                    ->orWhere('countries.country_name', 'LIKE', '%'.$term.'%');
                            }
                            
                            // Fuzzy patterns
                            foreach($fuzzyPatterns as $pattern) {
                                $query->orWhere('departures.title', 'REGEXP', $pattern)
                                    ->orWhere('departures.slug_url_pre', 'REGEXP', $pattern)
                                    ->orWhere('destinations.dest_name', 'REGEXP', $pattern)
                                    ->orWhere('countries.country_name', 'REGEXP', $pattern);
                            }
                        }) 
                        ->distinct('departures.created_at')
                        ->select('departures.id','departures.title','departures.slug_url_pre as slug1','departures.slug_url as slug2','departures.dep_dook_ref_id as slug3','departures.no_of_nights','departures.no_of_days','departures.price_currency','departures.price','departures.price_currency_usd','departures.price_usd','departures.price_hide_show','departures.image','destinations.dest_name','departures.featured','departures.dep_type')
                        ->orderBy('departures.featured', 'DESC')
                        ->get()
                        ->toArray();
            
            // Query 2: Experience-based search
            $experience_idp = Experience::where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('experience_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('experience_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('experience_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('status', 1)
                            ->pluck('id')
                            ->toArray();
            
            $departures2 = [];
            if(count($experience_idp) > 0){
                $departure_idp = DB::table('destination_experiences')->whereIn('experience_id',$experience_idp)
                                ->distinct()
                                ->pluck('departure_id')
                                ->toArray();
                $departures2 = Departure::whereIn('id', $departure_idp)
                        ->where('status', 1)
                        ->where('dep_type','=','package')
                        ->distinct('created_at')
                        ->select('id','title','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_nights','no_of_days','price_currency','price','price_currency_usd','price_usd','price_hide_show','image','featured','dep_type')
                        ->orderBy('featured', 'DESC')
                        ->get()
                        ->toArray();
            }
            
            // Query 3: Activity-based search
            $activity_idp = Activity::where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('activity_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('activity_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('activity_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('status', 1)
                            ->pluck('id')
                            ->toArray();
            
            $departures3 = [];
            if(count($activity_idp) > 0){
                $departure_idp1 = ActivityDeparture::whereIn('activity_id',$activity_idp)
                                ->distinct()
                                ->pluck('departure_id')
                                ->toArray();  
                $departures3 = Departure::whereIn('id', $departure_idp1)
                        ->where('status', 1)
                        ->distinct('created_at')
                        ->select('id','title','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_nights','no_of_days','price_currency','price','price_currency_usd','price_usd','price_hide_show','image','featured','dep_type')
                        ->orderBy('featured', 'DESC')
                        ->get()
                        ->toArray();
            }
            
            // Query 4: Tag-based search
            $tags = DB::table('tags')
                    ->where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                        $query->where('name', 'LIKE', '%'.$keyword.'%');
                        foreach($searchTerms as $term) {
                            $query->orWhere('name', 'LIKE', '%'.$term.'%');
                        }
                        foreach($fuzzyPatterns as $pattern) {
                            $query->orWhere('name', 'REGEXP', $pattern);
                        }
                    })
                    ->pluck('id')
                    ->toArray();
            
            $departures4 = Departure::join('departure_tags','departure_tags.departure_id','=','departures.id')
                        ->join('tags','tags.id','=','departure_tags.tag_id')
                        ->where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns){
                            $query->where('departures.title', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('departures.slug_url_pre', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('departures.dep_dook_ref_id', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('tags.name', 'LIKE', '%'.$keyword.'%');
                            
                            foreach($searchTerms as $term) {
                                $query->orWhere('departures.title', 'LIKE', '%'.$term.'%')
                                    ->orWhere('departures.slug_url_pre', 'LIKE', '%'.$term.'%')
                                    ->orWhere('tags.name', 'LIKE', '%'.$term.'%');
                            }
                            
                            foreach($fuzzyPatterns as $pattern) {
                                $query->orWhere('departures.title', 'REGEXP', $pattern)
                                    ->orWhere('departures.slug_url_pre', 'REGEXP', $pattern)
                                    ->orWhere('tags.name', 'REGEXP', $pattern);
                            }
                        })
                        ->distinct('departures.created_at')
                        ->select('departures.id','departures.title','departures.slug_url_pre as slug1','departures.slug_url as slug2','departures.dep_dook_ref_id as slug3','departures.no_of_nights','departures.no_of_days','departures.price_currency','departures.price','departures.price_currency_usd','departures.price_usd','departures.price_hide_show','departures.image','departures.featured','departures.dep_type')
                        ->where('departures.status', 1)
                        ->orderBy('departures.featured', 'DESC')
                        ->get()
                        ->toArray();

            // API Departures with natural language support
            $username = env('AGENT_CONNECT_USERNAME');
            $password = env('AGENT_CONNECT_PASSWORD');
            $headerArray = [
                'Username: ' . $username,
                'Password: ' . $password,
            ];
            $baseUrl = 'https://agent.dookinternational.com/api';
            $url = $baseUrl . '/departure/group-departure';
            $method = 'GET';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            
            $result = curl_exec($ch);
            $data1 = json_decode($result);
            $departures = $data1->Result ?? [];
            curl_close($ch);
                 
            $apiDepartures = [];
            foreach ($departures as $departure) {
                $departureArray = [
                    'id' => $departure->DookDepartureId,
                    'title' => strtok($departure->Name, '-'),
                    'slug1' => 'group-tours',
                    'slug2' => $departure->DookSlug,
                    'slug3' => $departure->DookDepartureId ?: 'N/A',
                    'no_of_nights' => (int) explode(' ', $departure->DayNight)[2], 
                    'no_of_days' => (int) explode(' ', $departure->DayNight)[0],
                    'price_currency' => '₹',
                    'price' => $departure->Price,
                    'price_currency_usd' => null,
                    'price_usd' => null,
                    'image' => $departure->DookImage[0] ?? '',
                    'dest_name' => $departure->Destination[0] ?? 'Unknown',
                    'featured' => $departure->BestSellingPackage ? 1 : 0,
                    'dep_type' => 'api'
                ];
                
                // Enhanced natural language fuzzy match
                if ($this->naturalLanguageMatch($departureArray, $keyword, $searchTerms)) {
                    $apiDepartures[] = $departureArray;
                }
            }

            // Merge and deduplicate packages
            $array_mearge = array_merge($departures1, $departures2, $departures3, $departures4, $apiDepartures);
            
            $unique = array();
            foreach ($array_mearge as $value) {
                $unique[$value['id']] = $value;        
            }

            $packages_unique = array_values($unique);
            
            // Sort by relevance score
            $packages_unique = $this->sortByRelevance($packages_unique, $keyword, $searchTerms);

            // Format package data
            foreach ($packages_unique as $key => $image) {
                $capitalize = strtolower($image['title']);
                $packages_unique[$key]['title'] = ucwords($capitalize);
                $packages_unique[$key]['dimage'] = $image['image'];
                $packages_unique[$key]['featured'] = $packages_unique[$key]['featured'] == 1 ? 'Best Selling' : '';
                
                if (isset($image['dep_type']) && $image['dep_type'] == 'api') {
                    $packages_unique[$key]['image'] = $image['image'];
                } else {
                    if (!empty($image['image'])) {
                        $packages_unique[$key]['image'] = generateSignedUrl('package/' . $image['image']);
                    } else {
                        $packages_unique[$key]['image'] = url('assets/images') . '/package-no-image.jpg';
                    }
                }
                
                if($image['dep_type'] == "main"){
                    $datePrice = DB::table('departure_dates')
                        ->where('departure_id',$image['id'])
                        ->select('price','price_usd')
                        ->where('date','>=',now())
                        ->orderBy('price','ASC')
                        ->first();
                    if($datePrice){
                        $packages_unique[$key]['price'] = $datePrice->price;
                        $packages_unique[$key]['price_usd'] = $datePrice->price_usd;
                    }
                } else {
                    $prices = DB::table('hotel_categories')
                        ->where('departure_id',$image['id'])
                        ->orderBy('price_inr','ASC')
                        ->first();
                    if(isset($prices)){
                        $packages_unique[$key]['price'] = $prices->price_inr;
                        $packages_unique[$key]['price_usd'] = $prices->price_usd;
                    }
                }
            }
            
            // ==================== COUNTRIES SEARCH ====================
            
            $countries_data1 = Country::where('status',1)
                             ->where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                 $query->where('country_name', 'LIKE', '%'.$keyword.'%');
                                 foreach($searchTerms as $term) {
                                     $query->orWhere('country_name', 'LIKE', '%'.$term.'%');
                                 }
                                 foreach($fuzzyPatterns as $pattern) {
                                     $query->orWhere('country_name', 'REGEXP', $pattern);
                                 }
                             })
                             ->distinct()
                             ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                             ->get()
                             ->toArray();
            
            $experience_id = Experience::where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('experience_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('experience_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('experience_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('status', 1)
                            ->pluck('id')
                            ->toArray();

            $departure_id = DB::table('destination_experiences')->whereIn('experience_id',$experience_id)
                            ->distinct()
                            ->pluck('departure_id')
                            ->toArray();
            $country_id = CountryDeparture::whereIn('departure_id',$departure_id)
                            ->distinct()
                            ->pluck('country_id')
                            ->toArray();
            $countries_data2 = Country::where('status',1)
                             ->whereIn('id', $country_id)
                             ->distinct()
                             ->where('slug_url','!=','')
                             ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                             ->get()
                             ->toArray();
            
            $country_id1 = Destination::where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('dest_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('dest_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('dest_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('status', 1)
                            ->pluck('country_id')
                            ->toArray();
            $countries_data3 = Country::where('status',1)
                             ->whereIn('id', $country_id1)
                             ->distinct()
                             ->where('slug_url','!=','')
                             ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                             ->get()
                             ->toArray();

            $activity_id = Activity::where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('activity_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('activity_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('activity_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('status', 1)
                            ->pluck('id')
                            ->toArray();
            $departure_id2 = ActivityDeparture::whereIn('activity_id',$activity_id)
                            ->distinct()
                            ->pluck('departure_id')
                            ->toArray();
            $country_id2 = CountryDeparture::whereIn('departure_id',$departure_id2)
                            ->distinct()
                            ->pluck('country_id')
                            ->toArray();
            $countries_data4 = Country::where('status',1)
                             ->whereIn('id', $country_id2)
                             ->distinct()
                             ->where('slug_url','!=','')
                             ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                             ->get()
                             ->toArray();
            
            $departures_idc = Departure::join('departure_tags','departure_tags.departure_id','=','departures.id')
                        ->join('tags','tags.id','=','departure_tags.tag_id')
                        ->where('departures.status', 1)
                        ->where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns){
                            $query->where('departures.title', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('departures.slug_url_pre', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('departures.dep_dook_ref_id', 'LIKE', '%'.$keyword.'%')
                                ->orWhere('tags.name', 'LIKE', '%'.$keyword.'%');
                            
                            foreach($searchTerms as $term) {
                                $query->orWhere('departures.title', 'LIKE', '%'.$term.'%')
                                    ->orWhere('departures.slug_url_pre', 'LIKE', '%'.$term.'%')
                                    ->orWhere('tags.name', 'LIKE', '%'.$term.'%');
                            }
                            
                            foreach($fuzzyPatterns as $pattern) {
                                $query->orWhere('departures.title', 'REGEXP', $pattern)
                                    ->orWhere('departures.slug_url_pre', 'REGEXP', $pattern)
                                    ->orWhere('tags.name', 'REGEXP', $pattern);
                            }
                        })
                        ->distinct('departures.created_at')
                        ->pluck('departures.id')
                        ->toArray();
            $country_idc = CountryDeparture::whereIn('departure_id',$departures_idc)
                            ->distinct()
                            ->pluck('country_id')
                            ->toArray();
            $countries_data5 = Country::where('status',1)
                             ->whereIn('id', $country_idc)
                             ->distinct()
                             ->where('slug_url','!=','')
                             ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                             ->get()
                             ->toArray();
            
            $countries_data6 = Country::where('status',1)
                             ->whereIn('id', $country_idc)
                             ->distinct()
                             ->where('slug_url','!=','')
                             ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
                             ->get()
                             ->toArray();
            
            $array_mearge_country = array_merge($countries_data1, $countries_data2, $countries_data3, $countries_data4, $countries_data5, $countries_data6);

            $unique_country = array();
            foreach ($array_mearge_country as $value_country) {
                $unique_country[$value_country['id']] = $value_country;
            }
            $countries_data = array_values($unique_country);
            foreach ($countries_data as $key => $valueContry) {
                if($valueContry['image'] != "" && $valueContry['image'] != null){
                    $countries_data[$key]['image'] = generateSignedUrl('country/'.$valueContry['image']);
                } else {
                    $countries_data[$key]['image'] = url('images').'/poi-no-image.jpg';
                }
                $countries_data[$key]['about_country_slug_url'] = "";
                $countries_data[$key]['country_attraction_slug_url'] = "";
            }
            
            // ==================== DESTINATIONS SEARCH ====================
            
            $destination_data1 = Destination::where('status',1)
                            ->where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('dest_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('dest_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('dest_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('slug_url','!=','')
                            ->select('id','dest_name','slug_url','image')
                            ->get()
                            ->toArray();
            
            $experience_idd = Experience::where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('experience_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('experience_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('experience_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('status', 1)
                            ->pluck('id')
                            ->toArray();
            $destination_id = DB::table('destination_experiences')->whereIn('experience_idd',$experience_idd)
                            ->distinct()
                            ->pluck('destination_id')
                            ->toArray();
            $destination_data2 = Destination::where('status',1)
                            ->whereIn('id', $destination_id)
                            ->distinct()
                            ->where('slug_url','!=','')
                            ->select('id','dest_name','slug_url','image')
                            ->get()
                            ->toArray();
            
            $activity_idd = Activity::where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                $query->where('activity_name', 'LIKE', '%'.$keyword.'%');
                                foreach($searchTerms as $term) {
                                    $query->orWhere('activity_name', 'LIKE', '%'.$term.'%');
                                }
                                foreach($fuzzyPatterns as $pattern) {
                                    $query->orWhere('activity_name', 'REGEXP', $pattern);
                                }
                            })
                            ->distinct()
                            ->where('status', 1)
                            ->pluck('id')
                            ->toArray();
            $departure_idd = ActivityDeparture::whereIn('activity_id',$activity_idd)
                            ->distinct()
                            ->pluck('departure_id')
                            ->toArray();
            $destination_idd = DB::table('departure_destinations')
                            ->whereIn('departure_id',$departure_idd)
                            ->distinct()
                            ->pluck('destination_id')
                            ->toArray(); 

            $destination_data3 = Destination::where('status',1)
                            ->whereIn('id', $destination_idd)
                            ->distinct()
                            ->where('slug_url','!=','')
                            ->select('id','dest_name','slug_url','image')
                            ->get()
                            ->toArray();
            
            $countries_idd = Country::where('status',1)
                             ->where(function($query) use ($keyword, $searchTerms, $fuzzyPatterns) {
                                 $query->where('country_name', 'LIKE', '%'.$keyword.'%');
                                 foreach($searchTerms as $term) {
                                     $query->orWhere('country_name', 'LIKE', '%'.$term.'%');
                                 }
                                 foreach($fuzzyPatterns as $pattern) {
                                     $query->orWhere('country_name', 'REGEXP', $pattern);
                                 }
                             })
                             ->distinct()
                             ->pluck('id')
                             ->toArray();
            $destination_data4 = Destination::where('status',1)
                                ->whereIn('country_id', $countries_idd)
                                ->distinct()
                                ->where('slug_url','!=','')
                                ->select('id','dest_name','slug_url','image')
                                ->get()
                                ->toArray();
            
            $country_idd = CountryDeparture::whereIn('departure_id',$departures_idc)
                            ->distinct()
                            ->pluck('country_id')
                            ->toArray();
            $destination_data5 = Destination::where('status',1)
                                ->whereIn('country_id', $country_idd)
                                ->distinct()
                                ->where('slug_url','!=','')
                                ->select('id','dest_name','slug_url','image')
                                ->get()
                                ->toArray();
            
            $array_mearge_destination = array_merge($destination_data1, $destination_data2, $destination_data3, $destination_data4, $destination_data5);
            $unique_destination = array();
            foreach ($array_mearge_destination as $value_dest) {
                $unique_destination[$value_dest['id']] = $value_dest;
            }
            $destination_data = array_values($unique_destination);
            foreach ($destination_data as $key => $valueDest) {
                if($valueDest['image'] != "" && $valueDest['image'] != null){
                    $destination_data[$key]['image'] = generateSignedUrl('poi/'.$valueDest['image']);
                } else {
                    $destination_data[$key]['image'] = url('images').'/poi-no-image.jpg';
                }
            }
            
            $packages = $packages_unique;
            $countries = $countries_data;
            $destinations = $destination_data;
            $country_code = "";
            
            return view('frontend.common.search', compact('packages','countries','destinations','keyword','country_code')); 
        } 
    }

    /**
     * Extract meaningful terms by removing stop words and common conjunctions
     */
    private function extractMeaningfulTerms($keyword)
    {
        // Common stop words and conjunctions to remove
        $stopWords = [
            'and', 'or', 'the', 'a', 'an', 'in', 'on', 'at', 'to', 'for', 'of', 'with',
            'tour', 'tours', 'package', 'packages', 'trip', 'trips', 'travel', 
            'visit', 'explore', 'from', 'by', 'via', 'through', 'across'
        ];
        
        // Convert to lowercase and split
        $normalized = strtolower(trim($keyword));
        $normalized = preg_replace('/[^a-z0-9\s]/', ' ', $normalized);
        $words = preg_split('/\s+/', $normalized);
        
        // Filter out stop words and short words
        $meaningfulTerms = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) >= 3 && !in_array($word, $stopWords);
        });
        
        return array_values(array_unique($meaningfulTerms));
    }

    /**
     * Generate multiple fuzzy patterns for meaningful terms
     */
    private function generateMultipleFuzzyPatterns($searchTerms)
    {
        $patterns = [];
        
        foreach($searchTerms as $term) {
            if(strlen($term) < 3) continue;
            
            // Pattern 1: Allow 1-2 character variations
            $chars = str_split($term);
            $pattern = '';
            foreach($chars as $i => $char) {
                if($i > 0) {
                    $pattern .= '.{0,2}'; // Allow 0-2 character gap
                }
                $pattern .= $char;
            }
            $patterns[] = $pattern;
            
            // Pattern 2: Common phonetic/spelling variations
            $phoneticPattern = $this->createPhoneticPattern($term);
            if($phoneticPattern !== $term) {
                $patterns[] = $phoneticPattern;
            }
        }
        
        return array_unique($patterns);
    }

    /**
     * Create phonetic pattern for common misspellings
     */
    private function createPhoneticPattern($word)
    {
        // Common letter substitutions
        $substitutions = [
            'a' => '[aæàá]',
            'e' => '[eèéê]',
            'i' => '[iìíî]',
            'o' => '[oòóô]',
            'u' => '[uùúû]',
            'c' => '[ck]',
            'k' => '[ck]',
            's' => '[sz]',
            'z' => '[sz]',
            'f' => '[fph]',
            'ph' => '[fph]',
            'y' => '[yi]',
        ];
        
        $pattern = $word;
        foreach($substitutions as $from => $to) {
            $pattern = str_replace($from, $to, $pattern);
        }
        
        return $pattern;
    }

    /**
     * Natural language matching for API results
     */
    private function naturalLanguageMatch($departure, $keyword, $searchTerms)
    {
        $searchableText = strtolower(
            $departure['title'] . ' ' . 
            $departure['slug2'] . ' ' . 
            $departure['dest_name']
        );
        
        $keyword = strtolower($keyword);
        
        // Exact match
        if(stripos($searchableText, $keyword) !== false) {
            return true;
        }
        
        // If no meaningful terms extracted, return false
        if(empty($searchTerms)) {
            return false;
        }
        
        // Check if ANY meaningful term matches
        foreach($searchTerms as $term) {
            // Direct substring match
            if(stripos($searchableText, $term) !== false) {
                return true;
            }
            
            // Fuzzy match using Levenshtein distance
            $words = preg_split('/[\s\-_]+/', $searchableText);
            foreach($words as $word) {
                if(strlen($word) < 3) continue;
                
                // Calculate similarity
                $distance = levenshtein($word, $term);
                $maxDistance = max(1, floor(strlen($term) * 0.35)); // 35% tolerance
                
                if($distance <= $maxDistance) {
                    return true;
                }
                
                // Also check if term is contained in word
                if(strlen($term) >= 4 && stripos($word, $term) !== false) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Sort results by relevance score
     */
    private function sortByRelevance($packages, $keyword, $searchTerms)
    {
        usort($packages, function($a, $b) use ($keyword, $searchTerms) {
            $scoreA = $this->calculateRelevanceScore($a, $keyword, $searchTerms);
            $scoreB = $this->calculateRelevanceScore($b, $keyword, $searchTerms);
            
            // Sort by score first
            if($scoreA !== $scoreB) {
                return $scoreB <=> $scoreA;
            }
            
            // Then by featured status
            return $b['featured'] <=> $a['featured'];
        });
        
        return $packages;
    }

    /**
     * Calculate relevance score for ranking
     */
    private function calculateRelevanceScore($package, $keyword, $searchTerms)
    {
        $score = 0;
        $title = strtolower($package['title']);
        $keyword = strtolower($keyword);
        
        // Exact full keyword match in title = highest score
        if(stripos($title, $keyword) !== false) {
            $score += 100;
        }
        
        // Check destination name
        $destName = isset($package['dest_name']) ? strtolower($package['dest_name']) : '';
        if($destName && stripos($destName, $keyword) !== false) {
            $score += 80;
        }
        
        // Score each meaningful term
        foreach($searchTerms as $term) {
            // Exact term match in title
            if(stripos($title, $term) !== false) {
                $score += 50;
            }
            
            // Exact term match in destination
            if($destName && stripos($destName, $term) !== false) {
                $score += 40;
            }
            
            // Fuzzy match bonus
            $words = preg_split('/[\s\-_]+/', $title . ' ' . $destName);
            foreach($words as $word) {
                if(strlen($word) < 3) continue;
                
                $distance = levenshtein($word, $term);
                if($distance <= 2) {
                    $score += (2 - $distance) * 15; // Closer match = higher score
                }
            }
        }
        
        // Featured package bonus
        if($package['featured'] == 1 || $package['featured'] == 'Best Selling') {
            $score += 10;
        }
        
        return $score;
    }
    // public function commonSearch(Request $request)
    // {
    //     $keyword = $request->searchKeyword;
    //     $packages = array();
    //     $countries = array();
    //     $destinations = array();
        
    //     if($keyword){
    //         // Generate comprehensive fuzzy patterns
    //         $fuzzyPatterns = $this->generateComprehensiveFuzzyPatterns($keyword);
            
    //         // DEPARTURES SEARCH - Query 1
    //         $departures1 = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
    //                     ->join('destinations','destinations.id','=','departure_destinations.destination_id')
    //                     ->join('country_departures','country_departures.departure_id','=','departures.id')
    //                     ->join('countries','countries.id','=','country_departures.country_id')
    //                     ->where('departures.status', 1)
    //                     ->where('departures.dep_type','=','package')
    //                     ->where(function($query) use ($keyword, $fuzzyPatterns) {
    //                         $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, [
    //                             'departures.title',
    //                             'departures.slug_url_pre',
    //                             'departures.dep_dook_ref_id',
    //                             'destinations.dest_name',
    //                             'countries.country_name'
    //                         ]);
    //                     }) 
    //                     ->distinct('departures.created_at')
    //                     ->select(
    //                         'departures.id',
    //                         'departures.title',
    //                         'departures.slug_url_pre as slug1',
    //                         'departures.slug_url as slug2',
    //                         'departures.dep_dook_ref_id as slug3',
    //                         'departures.no_of_nights',
    //                         'departures.no_of_days',
    //                         'departures.price_currency',
    //                         'departures.price',
    //                         'departures.price_currency_usd',
    //                         'departures.price_usd',
    //                         'departures.price_hide_show',
    //                         'departures.image',
    //                         'destinations.dest_name',
    //                         'departures.featured',
    //                         'departures.dep_type'
    //                     )
    //                     ->orderBy('departures.featured', 'DESC')
    //                     ->get()
    //                     ->toArray();

    //         // EXPERIENCE SEARCH - Query 2
    //         $experience_idp = Experience::where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['experience_name']);
    //                         })
    //                         ->where('status', 1)
    //                         ->distinct()
    //                         ->pluck('id')
    //                         ->toArray();
            
    //         if(count($experience_idp) > 0){
    //             $departure_idp = DB::table('destination_experiences')
    //                             ->whereIn('experience_id', $experience_idp)
    //                             ->distinct()
    //                             ->pluck('departure_id')
    //                             ->toArray();
                
    //             $departures2 = Departure::whereIn('id', $departure_idp)
    //                     ->where('status', 1)
    //                     ->where('dep_type','=','package')
    //                     ->distinct('created_at')
    //                     ->select('id','title','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_nights','no_of_days','price_currency','price','price_currency_usd','price_usd','price_hide_show','image','featured','dep_type')
    //                     ->orderBy('featured', 'DESC')
    //                     ->get()
    //                     ->toArray();
    //         } else {
    //             $departures2 = [];
    //         }

    //         // ACTIVITY SEARCH - Query 3
    //         $activity_idp = Activity::where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['activity_name']);
    //                         })
    //                         ->where('status', 1)
    //                         ->distinct()
    //                         ->pluck('id')
    //                         ->toArray();
            
    //         if(count($activity_idp) > 0){
    //             $departure_idp1 = ActivityDeparture::whereIn('activity_id', $activity_idp)
    //                             ->distinct()
    //                             ->pluck('departure_id')
    //                             ->toArray();  
                
    //             $departures3 = Departure::whereIn('id', $departure_idp1)
    //                     ->where('status', 1)
    //                     ->distinct('created_at')
    //                     ->select('id','title','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_nights','no_of_days','price_currency','price','price_currency_usd','price_usd','price_hide_show','image','featured','dep_type')
    //                     ->orderBy('featured', 'DESC')
    //                     ->get()
    //                     ->toArray();
    //         } else {
    //             $departures3 = [];
    //         }

    //         // TAGS SEARCH - Query 4
    //         $tags = DB::table('tags')
    //                 ->where(function($query) use ($keyword, $fuzzyPatterns) {
    //                     $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['name']);
    //                 })
    //                 ->pluck('id')
    //                 ->toArray();
            
    //         $departures4 = [];
    //         if(count($tags) > 0) {
    //             $departures4 = Departure::join('departure_tags','departure_tags.departure_id','=','departures.id')
    //                         ->join('tags','tags.id','=','departure_tags.tag_id')
    //                         ->whereIn('tags.id', $tags)
    //                         ->where('departures.status', 1)
    //                         ->distinct('departures.created_at')
    //                         ->select('departures.id','departures.title','departures.slug_url_pre as slug1','departures.slug_url as slug2','departures.dep_dook_ref_id as slug3','departures.no_of_nights','departures.no_of_days','departures.price_currency','departures.price','departures.price_currency_usd','departures.price_usd','departures.price_hide_show','departures.image','departures.featured','departures.dep_type')
    //                         ->orderBy('departures.featured', 'DESC')
    //                         ->get()
    //                         ->toArray();
    //         }

    //         // API DEPARTURES
    //         $username = env('AGENT_CONNECT_USERNAME');
    //         $password = env('AGENT_CONNECT_PASSWORD');
    //         $headerArray = [
    //             'Username: ' . $username,
    //             'Password: ' . $password,
    //         ];
    //         $baseUrl = 'https://agent.dookinternational.com/api';
    //         $url = $baseUrl . '/departure/group-departure';
            
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            
    //         $result = curl_exec($ch);
    //         $data1 = json_decode($result);
    //         $departures = $data1->Result ?? [];
    //         curl_close($ch);
                  
    //         $apiDepartures = [];

    //         foreach ($departures as $departure) {
    //             $departureArray = [
    //                 'id' => $departure->DookDepartureId,
    //                 'title' => strtok($departure->Name, '-'),
    //                 'slug1' => 'group-tours',
    //                 'slug2' => $departure->DookSlug,
    //                 'slug3' => $departure->DookDepartureId ?: 'N/A',
    //                 'no_of_nights' => isset($departure->DayNight) ? (int)explode(' ', $departure->DayNight)[0] : 0,
    //                 'price_currency' => '₹',
    //                 'price' => $departure->Price,
    //                 'price_currency_usd' => null,
    //                 'price_usd' => null,
    //                 'image' => $departure->DookImage[1] ?? '',
    //                 'dest_name' => $departure->Destination[0] ?? 'Unknown',
    //                 'featured' => $departure->BestSellingPackage ? 1 : 0,
    //                 'dep_type' => 'api'
    //             ];
                
    //             // Fuzzy matching for API results
    //             if ($this->matchesAnyPattern($departureArray['title'], $keyword, $fuzzyPatterns) || 
    //                 $this->matchesAnyPattern($departureArray['slug2'], $keyword, $fuzzyPatterns) || 
    //                 $this->matchesAnyPattern($departureArray['dest_name'], $keyword, $fuzzyPatterns)) {
    //                 $apiDepartures[] = $departureArray;
    //             }
    //         }

    //         // MERGE AND DEDUPLICATE PACKAGES
    //         $array_mearge = array_merge($departures1, $departures2, $departures3, $departures4, $apiDepartures);
            
    //         $unique = array();
    //         foreach ($array_mearge as $value) {
    //             $unique[$value['id']] = $value;        
    //         }

    //         $packages_unique = array_values($unique);
            
    //         // Sort by featured status
    //         usort($packages_unique, function($a, $b) {
    //             return $b['featured'] <=> $a['featured'];
    //         });

    //         // Apply smart relevance filtering
    //         $packages_unique = $this->smartFilter($packages_unique, $keyword, $fuzzyPatterns, ['title', 'dest_name']);

    //         // FORMAT PACKAGE DATA
    //         foreach ($packages_unique as $key => $image) {
    //             $capitalize = strtolower($image['title']);
    //             $packages_unique[$key]['title'] = ucwords($capitalize);
    //             $packages_unique[$key]['dimage'] = $image['image'];
    //             $packages_unique[$key]['featured'] = $packages_unique[$key]['featured'] == 1 ? 'Best Selling' : '';
                
    //             if (isset($image['dep_type']) && $image['dep_type'] == 'api') {
    //                 $packages_unique[$key]['image'] = $image['image'];
    //             } else {
    //                 if (!empty($image['image'])) {  
    //                     $packages_unique[$key]['image'] = generateSignedUrl('package/' . $image['image']);
    //                 } else {
    //                     $packages_unique[$key]['image'] = url('assets/images') . '/package-no-image.jpg';
    //                 }
    //             }
                
    //             if($image['dep_type'] == "main"){
    //                 $datePrice = DB::table('departure_dates')
    //                     ->where('departure_id', $image['id'])
    //                     ->select('price','price_usd')
    //                     ->where('date','>=', now())
    //                     ->orderBy('price','ASC')
    //                     ->first();
    //                 if($datePrice){
    //                     $packages_unique[$key]['price'] = $datePrice->price;
    //                     $packages_unique[$key]['price_usd'] = $datePrice->price_usd;
    //                 }
    //             } else {
    //                 $prices = DB::table('hotel_categories')
    //                     ->where('departure_id', $image['id'])
    //                     ->orderBy('price_inr','ASC')
    //                     ->first();
    //                 if(isset($prices)){
    //                     $packages_unique[$key]['price'] = $prices->price_inr;
    //                     $packages_unique[$key]['price_usd'] = $prices->price_usd;
    //                 }
    //             }
    //         }

    //         // COUNTRIES SEARCH
    //         $countries_data1 = Country::where('status', 1)
    //                          ->where(function($query) use ($keyword, $fuzzyPatterns) {
    //                              $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['country_name']);
    //                          })
    //                          ->distinct()
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
            
    //         $experience_id = Experience::where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['experience_name']);
    //                         })
    //                         ->where('status', 1)
    //                         ->distinct()
    //                         ->pluck('id')
    //                         ->toArray();

    //         $departure_id = DB::table('destination_experiences')
    //                         ->whereIn('experience_id', $experience_id)
    //                         ->distinct()
    //                         ->pluck('departure_id')
    //                         ->toArray();
            
    //         $country_id = CountryDeparture::whereIn('departure_id', $departure_id)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
            
    //         $countries_data2 = Country::where('status', 1)
    //                          ->whereIn('id', $country_id)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();

    //         $country_id1 = Destination::where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['dest_name']);
    //                         })
    //                         ->where('status', 1)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
            
    //         $countries_data3 = Country::where('status', 1)
    //                          ->whereIn('id', $country_id1)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();

    //         $activity_id = Activity::where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['activity_name']);
    //                         })
    //                         ->where('status', 1)
    //                         ->distinct()
    //                         ->pluck('id')
    //                         ->toArray();
            
    //         $departure_id2 = ActivityDeparture::whereIn('activity_id', $activity_id)
    //                         ->distinct()
    //                         ->pluck('departure_id')
    //                         ->toArray();
            
    //         $country_id2 = CountryDeparture::whereIn('departure_id', $departure_id2)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
            
    //         $countries_data4 = Country::where('status', 1)
    //                          ->whereIn('id', $country_id2)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();

    //         $departures_idc = Departure::join('departure_tags','departure_tags.departure_id','=','departures.id')
    //                     ->join('tags','tags.id','=','departure_tags.tag_id')
    //                     ->where('departures.status', 1)
    //                     ->where(function($query) use ($keyword, $fuzzyPatterns) {
    //                         $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, [
    //                             'departures.title',
    //                             'departures.slug_url_pre',
    //                             'departures.dep_dook_ref_id',
    //                             'tags.name'
    //                         ]);
    //                     })
    //                     ->distinct('departures.created_at')
    //                     ->pluck('departures.id')
    //                     ->toArray();
            
    //         $country_idc = CountryDeparture::whereIn('departure_id', $departures_idc)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
            
    //         $countries_data5 = Country::where('status', 1)
    //                          ->whereIn('id', $country_id2)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
            
    //         $countries_data6 = Country::where('status', 1)
    //                          ->whereIn('id', $country_idc)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();

    //         $array_mearge_country = array_merge(
    //             $countries_data1, $countries_data2, $countries_data3, 
    //             $countries_data4, $countries_data5, $countries_data6
    //         );

    //         $unique_country = array();
    //         foreach ($array_mearge_country as $value_country) {
    //             $unique_country[$value_country['id']] = $value_country;
    //         }
            
    //         $countries_data = array_values($unique_country);
    //         $countries_data = $this->smartFilter($countries_data, $keyword, $fuzzyPatterns, ['countryName']);
            
    //         foreach ($countries_data as $key => $valueContry) {
    //             if($valueContry['image'] != "" && $valueContry['image'] != null){
    //                 $countries_data[$key]['image'] = generateSignedUrl('country/'.$valueContry['image']);
    //             } else {
    //                 $countries_data[$key]['image'] = url('images').'/poi-no-image.jpg';
    //             }
    //             $countries_data[$key]['about_country_slug_url'] = "";
    //             $countries_data[$key]['country_attraction_slug_url'] = "";
    //         }

    //         // DESTINATIONS SEARCH
    //         $destination_data1 = Destination::where('status', 1)
    //                         ->where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['dest_name']);
    //                         })
    //                         ->distinct()
    //                         ->where('slug_url','!=','')
    //                         ->select('id','dest_name','slug_url','image')
    //                         ->get()
    //                         ->toArray();

    //         $experience_idd = Experience::where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['experience_name']);
    //                         })
    //                         ->where('status', 1)
    //                         ->distinct()
    //                         ->pluck('id')
    //                         ->toArray();
            
    //         $destination_id = DB::table('destination_experiences')
    //                         ->whereIn('experience_id', $experience_idd)
    //                         ->distinct()
    //                         ->pluck('destination_id')
    //                         ->toArray();
            
    //         $destination_data2 = Destination::where('status', 1)
    //                         ->whereIn('id', $destination_id)
    //                         ->distinct()
    //                         ->where('slug_url','!=','')
    //                         ->select('id','dest_name','slug_url','image')
    //                         ->get()
    //                         ->toArray();

    //         $activity_idd = Activity::where(function($query) use ($keyword, $fuzzyPatterns) {
    //                             $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['activity_name']);
    //                         })
    //                         ->where('status', 1)
    //                         ->distinct()
    //                         ->pluck('id')
    //                         ->toArray();
            
    //         $departure_idd = ActivityDeparture::whereIn('activity_id', $activity_idd)
    //                         ->distinct()
    //                         ->pluck('departure_id')
    //                         ->toArray();
            
    //         $destination_idd = DB::table('departure_destinations')
    //                         ->whereIn('departure_id', $departure_idd)
    //                         ->distinct()
    //                         ->pluck('destination_id')
    //                         ->toArray(); 

    //         $destination_data3 = Destination::where('status', 1)
    //                         ->whereIn('id', $destination_idd)
    //                         ->distinct()
    //                         ->where('slug_url','!=','')
    //                         ->select('id','dest_name','slug_url','image')
    //                         ->get()
    //                         ->toArray();

    //         $countries_idd = Country::where('status', 1)
    //                          ->where(function($query) use ($keyword, $fuzzyPatterns) {
    //                              $this->applyFuzzySearch($query, $keyword, $fuzzyPatterns, ['country_name']);
    //                          })
    //                          ->distinct()
    //                          ->pluck('id')
    //                          ->toArray();
            
    //         $destination_data4 = Destination::where('status', 1)
    //                             ->whereIn('country_id', $countries_idd)
    //                             ->distinct()
    //                             ->where('slug_url','!=','')
    //                             ->select('id','dest_name','slug_url','image')
    //                             ->get()
    //                             ->toArray();

    //         $country_idd = CountryDeparture::whereIn('departure_id', $departures_idc)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
            
    //         $destination_data5 = Destination::where('status', 1)
    //                             ->whereIn('country_id', $country_idd)
    //                             ->distinct()
    //                             ->where('slug_url','!=','')
    //                             ->select('id','dest_name','slug_url','image')
    //                             ->get()
    //                             ->toArray();

    //         $array_mearge_destination = array_merge(
    //             $destination_data1, $destination_data2, $destination_data3, 
    //             $destination_data4, $destination_data5
    //         );
            
    //         $unique_destination = array();
    //         foreach ($array_mearge_destination as $value_dest) {
    //             $unique_destination[$value_dest['id']] = $value_dest;
    //         }
            
    //         $destination_data = array_values($unique_destination);
    //         $destination_data = $this->smartFilter($destination_data, $keyword, $fuzzyPatterns, ['dest_name']);
            
    //         foreach ($destination_data as $key => $valueDest) {
    //             if($valueDest['image'] != "" && $valueDest['image'] != null){
    //                 $destination_data[$key]['image'] = generateSignedUrl('poi/'.$valueDest['image']);
    //             } else {
    //                 $destination_data[$key]['image'] = url('images').'/poi-no-image.jpg';
    //             }
    //         }

    //         $packages = $packages_unique;
    //         $countries = $countries_data;
    //         $destinations = $destination_data;
    //         $country_code = "";
            
    //         return view('frontend.common.search', compact('packages','countries','destinations','keyword','country_code')); 
    //     } 
        
    //     return redirect()->back()->with('error', 'Please enter a search keyword');
    // }

    // // COMPREHENSIVE FUZZY PATTERN GENERATOR
    // private function generateComprehensiveFuzzyPatterns($keyword)
    // {
    //     $patterns = [];
    //     $keyword = strtolower(trim($keyword));
    //     $patterns[] = $keyword;
        
    //     $length = strlen($keyword);
    //     if ($length < 3) {
    //         return $patterns;
    //     }
        
    //     // 1. VOWEL SUBSTITUTIONS (a/e/i/o/u interchangeable)
    //     $vowelMap = ['a' => ['e','o'], 'e' => ['a','i'], 'i' => ['e','y'], 'o' => ['a','u'], 'u' => ['o']];
    //     for ($i = 0; $i < $length; $i++) {
    //         $char = $keyword[$i];
    //         if (isset($vowelMap[$char])) {
    //             foreach ($vowelMap[$char] as $sub) {
    //                 $patterns[] = substr_replace($keyword, $sub, $i, 1);
    //             }
    //         }
    //     }
        
    //     // 2. CONSONANT SUBSTITUTIONS (common confusions)
    //     $consonantMap = [
    //         's' => ['z', 'c'], 'z' => ['s'], 'c' => ['k', 's'], 'k' => ['c', 'q'],
    //         'f' => ['ph', 'v'], 'ph' => ['f'], 'v' => ['f', 'w'], 'w' => ['v'],
    //         'g' => ['j'], 'j' => ['g'], 'x' => ['ks'], 'q' => ['k']
    //     ];
    //     for ($i = 0; $i < $length; $i++) {
    //         $char = $keyword[$i];
    //         if (isset($consonantMap[$char])) {
    //             foreach ($consonantMap[$char] as $sub) {
    //                 if (strlen($sub) == 1) {
    //                     $patterns[] = substr_replace($keyword, $sub, $i, 1);
    //                 }
    //             }
    //         }
    //     }
        
    //     // 3. DOUBLE LETTER VARIATIONS (belarus <-> belaarus, baku <-> bakku)
    //     for ($i = 0; $i < $length - 1; $i++) {
    //         if ($keyword[$i] == $keyword[$i + 1]) {
    //             // Remove one duplicate
    //             $patterns[] = substr($keyword, 0, $i) . substr($keyword, $i + 1);
    //         } else {
    //             // Add duplicate
    //             $patterns[] = substr($keyword, 0, $i + 1) . $keyword[$i] . substr($keyword, $i + 1);
    //         }
    //     }
        
    //     // 4. TRANSPOSITIONS (swap adjacent characters - balarus <-> balaurs)
    //     for ($i = 0; $i < $length - 1; $i++) {
    //         $swapped = $keyword;
    //         $swapped[$i] = $keyword[$i + 1];
    //         $swapped[$i + 1] = $keyword[$i];
    //         $patterns[] = $swapped;
    //     }
        
    //     // 5. MISSING CHARACTERS (balarus <-> blarus, belarus <-> belrus)
    //     for ($i = 0; $i < $length; $i++) {
    //         $patterns[] = substr($keyword, 0, $i) . substr($keyword, $i + 1);
    //     }
        
    //     // 6. EXTRA CHARACTERS (common typos - adding a letter)
    //     $commonChars = ['a','e','i','o','u','s','r','t','n'];
    //     if ($length <= 8) {
    //         for ($i = 0; $i <= $length; $i++) {
    //             foreach ($commonChars as $char) {
    //                 $patterns[] = substr($keyword, 0, $i) . $char . substr($keyword, $i);
    //             }
    //         }
    //     }
        
    //     // 7. SILENT LETTERS (remove common silent letters)
    //     $silentLetters = ['h', 'e'];
    //     foreach ($silentLetters as $silent) {
    //         if (strpos($keyword, $silent) !== false) {
    //             $patterns[] = str_replace($silent, '', $keyword);
    //         }
    //     }
        
    //     // 8. COMMON WORD ENDINGS
    //     $endings = ['ia' => 'ya', 'ya' => 'ia', 'us' => 'as', 'as' => 'us'];
    //     foreach ($endings as $from => $to) {
    //         if (substr($keyword, -strlen($from)) === $from) {
    //             $patterns[] = substr($keyword, 0, -strlen($from)) . $to;
    //         }
    //     }
        
    //     // 9. PHONETIC PATTERNS (common sound-alike patterns)
    //     $phoneticReplacements = [
    //         'th' => ['t'], 't' => ['th'], 'ph' => ['f'], 'f' => ['ph'],
    //         'tion' => ['shun'], 'sion' => ['shun'], 'ough' => ['o', 'off'],
    //         'eau' => ['o'], 'oo' => ['u'], 'ee' => ['i']
    //     ];
    //     foreach ($phoneticReplacements as $from => $toArray) {
    //         if (strpos($keyword, $from) !== false) {
    //             foreach ($toArray as $to) {
    //                 $patterns[] = str_replace($from, $to, $keyword);
    //             }
    //         }
    //     }
        
    //     // 10. SPECIAL CASES FOR COUNTRY/CITY NAMES
    //     $specialCases = [
    //         'belar' => ['belar', 'belor', 'bela', 'byelo'],
    //         'baku' => ['baku', 'bakuu', 'bakoo', 'bako'],
    //         'dubai' => ['dubai', 'dubay', 'dubi', 'dubae'],
    //         'paris' => ['paris', 'parris', 'pari', 'peris'],
    //         'russia' => ['russia', 'rusiya', 'russa', 'rusia'],
    //         'turkey' => ['turkey', 'turky', 'turkiye', 'turkie'],
    //         'thailand' => ['thailand', 'thialand', 'tailand', 'thiland']
    //     ];
        
    //     foreach ($specialCases as $base => $variants) {
    //         if (strpos($keyword, $base) !== false || levenshtein($keyword, $base) <= 2) {
    //             $patterns = array_merge($patterns, $variants);
    //         }
    //     }
        
    //     // Remove patterns that are too short or too different
    //     $patterns = array_filter($patterns, function($p) use ($length) {
    //         return strlen($p) >= max(3, $length - 3) && strlen($p) <= $length + 3;
    //     });
        
    //     return array_unique($patterns);
    // }

    // // APPLY FUZZY SEARCH TO QUERY
    // private function applyFuzzySearch($query, $keyword, $fuzzyPatterns, $fields)
    // {
    //     $query->where(function($q) use ($keyword, $fuzzyPatterns, $fields) {
    //         foreach ($fields as $field) {
    //             // Exact match (highest priority)
    //             $q->orWhere($field, 'LIKE', '%'.$keyword.'%');
                
    //             // Fuzzy pattern matches
    //             foreach ($fuzzyPatterns as $pattern) {
    //                 if ($pattern !== $keyword) {
    //                     $q->orWhere($field, 'LIKE', '%'.$pattern.'%');
    //                 }
    //             }
    //         }
    //     });
    // }

    // // CHECK IF TEXT MATCHES ANY PATTERN
    // private function matchesAnyPattern($text, $keyword, $fuzzyPatterns)
    // {
    //     $text = strtolower($text);
    //     $keyword = strtolower($keyword);
        
    //     // Exact substring match
    //     if (stripos($text, $keyword) !== false) {
    //         return true;
    //     }
        
    //     // Check all fuzzy patterns
    //     foreach ($fuzzyPatterns as $pattern) {
    //         if (stripos($text, $pattern) !== false) {
    //             return true;
    //         }
    //     }
        
    //     // Levenshtein distance check (for very close matches)
    //     if (strlen($keyword) >= 4) {
    //         // Split text into words and check each word
    //         $words = preg_split('/\s+/', $text);
    //         foreach ($words as $word) {
    //             $distance = levenshtein($word, $keyword);
    //             $maxDistance = max(2, floor(strlen($keyword) * 0.25)); // 25% tolerance
    //             if ($distance <= $maxDistance) {
    //                 return true;
    //             }
    //         }
    //     }
        
    //     return false;
    // }

    // // SMART FILTER TO REMOVE IRRELEVANT RESULTS
    // private function smartFilter($items, $keyword, $fuzzyPatterns, $fields)
    // {
    //     $keyword = strtolower($keyword);
    //     $keywordLength = strlen($keyword);
        
    //     // For very short keywords (1-3 chars), be extremely strict
    //     if ($keywordLength <= 3) {
    //         return array_filter($items, function($item) use ($keyword, $fields) {
    //             foreach ($fields as $field) {
    //                 if (isset($item[$field])) {
    //                     $value = strtolower($item[$field]);
    //                     // Only match at word boundaries or at the start
    //                     if (preg_match('/\b' . preg_quote($keyword, '/') . '/i', $value) ||
    //                         strpos($value, $keyword) === 0) {
    //                         return true;
    //                     }
    //                 }
    //             }
    //             return false;
    //         });
    //     }
        
    //     // For medium keywords (4-6 chars), use moderate fuzzy matching
    //     if ($keywordLength <= 6) {
    //         return array_filter($items, function($item) use ($keyword, $fuzzyPatterns, $fields) {
    //             foreach ($fields as $field) {
    //                 if (isset($item[$field])) {
    //                     $value = strtolower($item[$field]);
                        
    //                     // Check exact substring
    //                     if (stripos($value, $keyword) !== false) {
    //                         return true;
    //                     }
                        
    //                     // Check fuzzy patterns
    //                     foreach ($fuzzyPatterns as $pattern) {
    //                         if (stripos($value, $pattern) !== false) {
    //                             return true;
    //                         }
    //                     }
                        
    //                     // Check word-level Levenshtein distance
    //                     $words = preg_split('/\s+/', $value);
    //                     foreach ($words as $word) {
    //                         if (levenshtein($word, $keyword) <= 2) {
    //                             return true;
    //                         }
    //                     }
    //                 }
    //             }
    //             return false;
    //         });
    //     }
        
    //     // For longer keywords (7+ chars), use full fuzzy matching with similarity
    //     return array_filter($items, function($item) use ($keyword, $fuzzyPatterns, $fields) {
    //         foreach ($fields as $field) {
    //             if (isset($item[$field])) {
    //                 $value = strtolower($item[$field]);
                    
    //                 // Check exact substring
    //                 if (stripos($value, $keyword) !== false) {
    //                     return true;
    //                 }
                    
    //                 // Check fuzzy patterns
    //                 foreach ($fuzzyPatterns as $pattern) {
    //                     if (stripos($value, $pattern) !== false) {
    //                         return true;
    //                     }
    //                 }
                    
    //                 // Check similarity (60% threshold)
    //                 similar_text($value, $keyword, $percent);
    //                 if ($percent >= 60) {
    //                     return true;
    //                 }
                    
    //                 // Check word-level matching
    //                 $words = preg_split('/\s+/', $value);
    //                 foreach ($words as $word) {
    //                     if (strlen($word) >= 4 && levenshtein($word, $keyword) <= 3) {
    //                         return true;
    //                     }
    //                 }
    //             }
    //         }
    //         return false;
    //     });
    // }
    // public function commonSearch(Request $request)
    // {
    //     $keyword = $request->searchKeyword;
    //     $packages = array();
    //     $countries = array();
    //     $destinations = array();
    //     //dd($keyword);
    //     if($keyword){
    //         $departures1 = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
    //                     ->join('destinations','destinations.id','=','departure_destinations.destination_id')
    //                     ->join('country_departures','country_departures.departure_id','=','departures.id')
    //                     ->join('countries','countries.id','=','country_departures.country_id')
    //                     ->where('departures.status', 1)
    //                     ->where('departures.dep_type','=','package')
    //                     ->where(function($query)use($keyword){
    //                         $query->where('departures.title', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('departures.slug_url_pre', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('destinations.dest_name', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('countries.country_name', 'LIKE','%'.$keyword.'%');
    //                     }) 
    //                     ->distinct('departures.created_at')
    //                     ->select('departures.id','departures.title','departures.slug_url_pre as slug1','departures.slug_url as slug2','departures.dep_dook_ref_id as slug3','departures.no_of_nights','departures.no_of_days','departures.price_currency','departures.price','departures.price_currency_usd','departures.price_usd','departures.price_hide_show','departures.image','destinations.dest_name','departures.featured','departures.dep_type')
                        
    //                     ->orderBy('departures.featured', 'DESC')
    //                     ->get()
    //                     ->toArray();
    //         $experience_idp = Experience::where('experience_name', 'LIKE','%'.$keyword.'%')
    //                         //->orWhere('description', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('status', 1)
    //                         ->pluck('id')
    //                         ->toArray();
    //         //dd($experience_idp);
    //         if(count($experience_idp)>0){
    //             $departure_idp = DB::table('destination_experiences')->whereIn('experience_id',$experience_idp)
    //                             ->distinct()
    //                             ->pluck('departure_id')
    //                             ->toArray();
    //             $departures2 = Departure::whereIn('id', $departure_idp)
    //                     ->where('status', 1)
    //                     ->where('dep_type','=','package')
    //                     ->distinct('created_at')
    //                     ->select('id','title','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_nights','no_of_days','price_currency','price','price_currency_usd','price_usd','price_hide_show','image','featured','dep_type')
    //                     ->orderBy('featured', 'DESC')
    //                     ->get()
    //                     ->toArray();
    //                     //dd($departures2);
    //         }
    //         else{
    //             $departures2 = [];
    //         }
    //         $activity_idp = Activity::where('activity_name', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('status', 1)
    //                         ->pluck('id')
    //                         ->toArray();
    //         if(count($activity_idp)>0){
    //             $departure_idp1 = ActivityDeparture::whereIn('activity_id',$activity_idp)
    //                             ->distinct()
    //                             ->pluck('departure_id')
    //                             ->toArray();  
    //             $departures3 = Departure::whereIn('id', $departure_idp1)
    //                     ->where('status', 1)
    //                     ->distinct('created_at')
    //                     ->select('id','title','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_nights','no_of_days','price_currency','price','price_currency_usd','price_usd','price_hide_show','image','featured','dep_type')
    //                     ->orderBy('featured', 'DESC')
    //                     ->get()
    //                     ->toArray();
    //         }
    //         else{
    //             $departures3 = [];
    //         }
    //         //dd($departures3);
    //         $tags = DB::table('tags')->where('name','LIKE','%'.$keyword.'%')->value('id');
    //         $dep_tags = DB::table('departure_tags')->where('tag_id', $tags)
    //                     ->pluck('departure_id')
    //                     ->toArray();
    //         $departures4 = Departure::join('departure_tags','departure_tags.departure_id','=','departures.id')->join('tags','tags.id','=','departure_tags.tag_id')
    //                     ->where(function($query)use($keyword){
    //                         $query->where('departures.title', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('departures.slug_url_pre', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keyword.'%')
    //                             //->orWhere('tour_classes.name', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('tags.name', 'LIKE','%'.$keyword.'%');
    //                     })
    //                     ->distinct('departures.created_at')
    //                     ->select('departures.id','departures.title','departures.slug_url_pre as slug1','departures.slug_url as slug2','departures.dep_dook_ref_id as slug3','departures.no_of_nights','departures.no_of_days','departures.price_currency','departures.price','departures.price_currency_usd','departures.price_usd','departures.price_hide_show','departures.image','departures.featured','departures.dep_type')
    //                     ->where('departures.status', 1)
    //                     ->orderBy('departures.featured', 'DESC')
    //                     ->get()
    //                     ->toArray();

    //            // for departure    
    //             $username = env('AGENT_CONNECT_USERNAME');
    //             $password = env('AGENT_CONNECT_PASSWORD');
    //             $headerArray = [
    //                 'Username: ' . $username,
    //                 'Password: ' . $password,
    //             ];
    //             $baseUrl = 'https://agent.dookinternational.com/api';
    //             $url = $baseUrl . '/departure/group-departure';
    //             $method = 'GET';
                
    //             $ch = curl_init();
    //             curl_setopt($ch, CURLOPT_URL, $url);
    //             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);

    //             if(isset($bodyArray)){
    //                 curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyArray);
    //             }
                
    //             $result=curl_exec($ch);
    //             $data1 =json_decode($result);
    //             $departures = $data1->Result;

    //             curl_close($ch);
                     
    //           $apiDepartures = [];
      
    //             foreach ($departures as $departure) {
                   
    //                 $departureArray = [
    //                     'id' => $departure->DookDepartureId,
    //                     'title' => strtok($departure->Name, '-'),
    //                     'slug1' => 'group-tours',
    //                     'slug2' => $departure->DookSlug,
    //                     'slug3' => $departure->DookDepartureId ?: 'N/A',
    //                     'no_of_nights' => (int) explode(' ', $departure->DayNight)[2], 
    //                     'no_of_days' => (int) explode(' ', $departure->DayNight)[0],
    //                     'price_currency' => '₹',
    //                     'price' => $departure->Price,
    //                     'price_currency_usd' => Null,
    //                     'price_usd' => NULL,
    //                     'image' => $departure->DookImage[0] ?? '',
    //                     'dest_name' => $departure->Destination[0] ?? 'Unknown',
    //                     'featured' => $departure->BestSellingPackage ? 1 : 0,
    //                     'dep_type' => 'api'
    //                 ];
    //                 if (stripos($departureArray['title'], $keyword) !== false || 
    //                     stripos($departureArray['slug2'], $keyword) !== false || 
    //                     stripos($departureArray['dest_name'], $keyword) !== false) {
    //                     $apiDepartures[] = $departureArray;
    //                 }
    //             }
       

    //         $array_mearge = array_merge($departures1, $departures2, $departures3, $departures4,$apiDepartures);
            
    //         $unique = array();
    //         foreach ($array_mearge as $value)
    //         {
    //             $unique[$value['id']] = $value;        
    //         }

    //         $packages_unique = array_values($unique);
    //         usort($packages_unique, function($a, $b) {
    //             return $b['featured'] <=> $a['featured']; 
    //         });


            

    //         foreach ($packages_unique as $key => $image) {
    //             $capitalize = strtolower($image['title']);
    //             $packages_unique[$key]['title'] = ucwords($capitalize);
    //             $packages_unique[$key]['dimage'] = $image['image'];
    //             $packages_unique[$key]['featured'] =$packages_unique[$key]['featured'] == 1?'Best Selling':'';
    //              if (isset($image['dep_type']) && $image['dep_type'] == 'api') {
    //             $packages_unique[$key]['image'] =  $image['image'];
    //                 } else {
    //                     if (!empty($image['image'])) {
    //                         $packages_unique[$key]['image'] = generateSignedUrl('package/' . $image['image']);
    //                     } else {
    //                         $packages_unique[$key]['image'] = url('assets/images') . '/package-no-image.jpg';
    //                     }
    //                 }
    //             if($image['dep_type'] == "main"){
    //                 $datePrice = DB::table('departure_dates')
    //                     ->where('departure_id',$image['id'])
    //                     ->select('price','price_usd')
    //                     ->where('date','>=',now())
    //                     ->orderBy('price','ASC')
    //                     ->first();
    //                 if($datePrice){
    //                     $packages_unique[$key]['price'] = $datePrice->price;
    //                     $packages_unique[$key]['price_usd'] = $datePrice->price_usd;
    //                 }
    //             }else{
    //                 $prices = DB::table('hotel_categories')
    //                     ->where('departure_id',$image['id'])
    //                     ->orderBy('price_inr','ASC')
    //                     ->first();
    //                 if(isset($prices)){
    //                     $packages_unique[$key]['price'] = $prices->price_inr;
    //                     $packages_unique[$key]['price_usd'] = $prices->price_usd;
    //                 }
    //             }
                
    //         }
    //         // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
    //         // Countries Data Get
    //         $countries_data1 = Country::where('status',1)
    //                          ->where('country_name', 'LIKE','%'.$keyword.'%')
    //                          ->distinct()
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
            
    //         $experience_id = Experience::where('experience_name', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('status', 1)
    //                         ->pluck('id')
    //                         ->toArray();

    //         $departure_id = DB::table('destination_experiences')->whereIn('experience_id',$experience_id)
    //                         ->distinct()
    //                         ->pluck('departure_id')
    //                         ->toArray();
    //         $country_id = CountryDeparture::whereIn('departure_id',$departure_id)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
    //         $countries_data2 = Country::where('status',1)
    //                          ->whereIn('id', $country_id)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
    //         //////////////////////////////////////////
    //         $country_id1 = Destination::where('dest_name', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('status', 1)
    //                         ->pluck('country_id')
    //                         ->toArray();
    //         $countries_data3 = Country::where('status',1)
    //                          ->whereIn('id', $country_id1)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
    //         //////////////////////////////////////////////

    //         $activity_id = Activity::where('activity_name', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('status', 1)
    //                         ->pluck('id')
    //                         ->toArray();
    //         $departure_id2 = ActivityDeparture::whereIn('activity_id',$activity_id)
    //                         ->distinct()
    //                         ->pluck('departure_id')
    //                         ->toArray();
    //         $country_id2 = CountryDeparture::whereIn('departure_id',$departure_id2)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
    //         $countries_data4 = Country::where('status',1)
    //                          ->whereIn('id', $country_id2)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
    //         $departures_idc = Departure::join('departure_tags','departure_tags.departure_id','=','departures.id')->join('tags','tags.id','=','departure_tags.tag_id')
    //                     ->where('departures.status', 1)
    //                     ->where(function($query)use($keyword){
    //                         $query->where('departures.title', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('departures.slug_url_pre', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keyword.'%')
    //                             //->orWhere('tour_classes.name', 'LIKE','%'.$keyword.'%')
    //                             ->orWhere('tags.name', 'LIKE','%'.$keyword.'%');
    //                     })
    //                     ->distinct('departures.created_at')
    //                     ->pluck('departures.id')
    //                     ->toArray();
    //         $country_idc = CountryDeparture::whereIn('departure_id',$departures_idc)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
    //         $countries_data5 = Country::where('status',1)
    //                          ->whereIn('id', $country_id2)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
    //         $country_idc = CountryDeparture::whereIn('departure_id',$departures_idc)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
    //                         //dd($country_idc);
    //         $countries_data6 = Country::where('status',1)
    //                          ->whereIn('id', $country_idc)
    //                          ->distinct()
    //                          ->where('slug_url','!=','')
    //                          ->select('id','country_name as countryName','country_exist','slug_url','about_country_slug_url','country_attraction_slug_url','description','image')
    //                          ->get()
    //                          ->toArray();
    //         $array_mearge_country = array_merge($countries_data1, $countries_data2, $countries_data3, $countries_data4, $countries_data5, $countries_data6);

    //         $unique_country = array();
    //         foreach ($array_mearge_country as $value_country)
    //         {
    //             $unique_country[$value_country['id']] = $value_country;
    //         }
    //         $countries_data = array_values($unique_country);
    //         foreach ($countries_data as $key => $valueContry) {
    //             if($valueContry['image'] != "" || $valueContry['image'] != null){
    //                 $countries_data[$key]['image'] = generateSignedUrl('country/'.$valueContry['image']);
    //             }else{
    //                 $countries_data[$key]['image'] = url('images').'/poi-no-image.jpg';
    //             }
    //             $countries_data[$key]['about_country_slug_url'] = "";
    //             $countries_data[$key]['country_attraction_slug_url'] = "";
    //         }
    //         //print_r($countries_data);
    //         // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
            
    //         // Destination Data Get
    //         $destination_data1 = Destination::where('status',1)
    //                         ->where('dest_name', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('slug_url','!=','')
    //                         ->select('id','dest_name','slug_url','image')
    //                         ->get()
    //                         ->toArray();
    //         $experience_idd = Experience::where('experience_name', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('status', 1)
    //                         ->pluck('id')
    //                         ->toArray();
    //         $destination_id = DB::table('destination_experiences')->whereIn('experience_id',$experience_idd)
    //                         ->distinct()
    //                         ->pluck('destination_id')
    //                         ->toArray();
    //         $destination_data2 = Destination::where('status',1)
    //                         ->whereIn('id', $destination_id)
    //                         ->distinct()
    //                         ->where('slug_url','!=','')
    //                         ->select('id','dest_name','slug_url','image')
    //                         ->get()
    //                         ->toArray();
    //         $activity_idd = Activity::where('activity_name', 'LIKE','%'.$keyword.'%')
    //                         ->distinct()
    //                         ->where('status', 1)
    //                         ->pluck('id')
    //                         ->toArray();
    //         $departure_idd = ActivityDeparture::whereIn('activity_id',$activity_idd)
    //                         ->distinct()
    //                         ->pluck('departure_id')
    //                         ->toArray();
    //         $destination_idd = DB::table('departure_destinations')
    //                         ->whereIn('departure_id',$departure_idd)
    //                         ->distinct()
    //                         ->pluck('destination_id')
    //                         ->toArray(); 

    //         $destination_data3 = Destination::where('status',1)
    //                         ->whereIn('id', $destination_idd)
    //                         ->distinct()
    //                         ->where('slug_url','!=','')
    //                         ->select('id','dest_name','slug_url','image')
    //                         ->get()
    //                         ->toArray();
    //         $countries_idd = Country::where('status',1)
    //                          ->where('country_name', 'LIKE','%'.$keyword.'%')
    //                          ->distinct()
    //                          ->pluck('id')
    //                          ->toArray();
    //         $destination_data4 = Destination::where('status',1)
    //                             ->whereIn('country_id', $countries_idd)
    //                             ->distinct()
    //                             ->where('slug_url','!=','')
    //                             ->select('id','dest_name','slug_url','image')
    //                             ->get()
    //                             ->toArray();
    //         $country_idd = CountryDeparture::whereIn('departure_id',$departures_idc)
    //                         ->distinct()
    //                         ->pluck('country_id')
    //                         ->toArray();
    //         $destination_data5 = Destination::where('status',1)
    //                             ->whereIn('country_id', $country_idd)
    //                             ->distinct()
    //                             ->where('slug_url','!=','')
    //                             ->select('id','dest_name','slug_url','image')
    //                             ->get()
    //                             ->toArray();
    //         $array_mearge_destination = array_merge($destination_data1, $destination_data2, $destination_data3, $destination_data4, $destination_data5);
    //         $unique_destination = array();
    //         foreach ($array_mearge_destination as $value_dest)
    //         {
    //             $unique_destination[$value_dest['id']] = $value_dest;
    //         }
    //         $destination_data = array_values($unique_destination);
    //         foreach ($destination_data as $key => $valueDest) {
    //             if($valueDest['image'] != "" || $valueDest['image'] != null){
    //                 $destination_data[$key]['image'] = generateSignedUrl('poi/'.$valueDest['image']);
    //             }else{
    //                 $destination_data[$key]['image'] = url('images').'/poi-no-image.jpg';
    //             }
    //         }
    //         $packages = $packages_unique;
    //         $countries = $countries_data;
    //         $destinations = $destination_data;
    //         $country_code = "";
    //         return view('frontend.common.search', compact('packages','countries','destinations','keyword','country_code')); 
    //     } 
    // }
}
