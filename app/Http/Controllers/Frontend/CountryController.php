<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\LandingDeparturePage;
use App\Models\DepartureDestinationPointOfInterest;
use App\Models\ActivityPointOfInterest;
use App\Models\DestinationExperience;
use App\Models\CountryExistingPoi;
use App\Models\CountryBestTimeToVisit;
use App\Models\CountryClimateType;
use App\Models\SlugMaster;
use App\Models\Destination;
use App\Models\Experience;
use Illuminate\Support\Str;
use DB;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index() {
        $countries = Country::where('status',1)->where('slug_url','!=','')->select('id', 'country_name as countryName', 'country_exist', 'slug_url', 'about_country_slug_url', 'country_attraction_slug_url', 'description', 'image')->orderBy('country_exist', 'DESC')->distinct()->paginate(12);
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
            return redirect('/404');
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
         if ($check_url) {
            if ($check_url->module_name == "country_tour_page") {
                 $countries = Country::where('slug_url', $slug)->select('id', 'country_name as countryName', 'title', 'sub_title as subTitle', 'official_name as officialName', 'capital', 'largest_city as largestCity', 'iso_3 as iso', 'currency', 'currency_symbol as currencySymbol', 'currency_code as currencyCode', 'national_language as nationalLanguage', 'drives_on as driveSide', 'area_unit as areaUnit', 'area', 'population', 'description', 'text_1', 'image_1', 'text_2', 'image_2', 'text_3', 'image_3', 'text_4', 'banner_image', 'flag', 'meta_title', 'meta_keywords', 'meta_description','mobile_banner_image')->first(); 
                 if ($countries) {
                     $countries->image_1 = env('AWS_BUCKET_URL') . '/country/' . $countries->image_1;
                    $countries->image_2 = env('AWS_BUCKET_URL') . '/country/' . $countries->image_2;
                    $countries->image_3 = env('AWS_BUCKET_URL') . '/country/' . $countries->image_3;
                  $countries->bestTimeToVisits = CountryBestTimeToVisit::where('country_id', $countries->id)
                        ->get();
                    $countries->climateTypes = CountryClimateType::select('climate_type_name as name')
                        ->where('country_id', $countries->id)
                        ->get();
                    $departures = DB::table('departures as d')
                        ->join('country_departures as cd', 'cd.departure_id', '=', 'd.id')
                        ->where('cd.country_id', $countries->id)->where('d.status', 1)->where('d.dep_type', 'package')
                        ->select('d.id', 'd.title', 'd.price_currency', 'd.price', 'd.price_currency_usd', 'd.price_usd', 'd.book_online', 'd.price_hide_show', 'd.slug_url_pre as slug1', 'd.slug_url as slug2', 'd.dep_dook_ref_id as slug3', 'd.no_of_days', 'd.no_of_nights', 'd.image', 'd.featured', 'd.dep_type')
                        ->distinct()->orderBy('d.featured', 'DESC')->paginate(8);
                        foreach ($departures as $departure) {

                        $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                        $departure->poi_names = $poiNames;
                        $wordLimit = 4;
                        $departure->title = ucwords(strtolower(implode(' ', array_slice(explode(' ', $departure->title), 0, $wordLimit))));
                        $departure->dimage = $departure->image;
                        $departure->image = $departure->image ? env('AWS_BUCKET_URL') . '/package/' . $departure->image : url('images') . '/package-no-image.jpg';
                        $departure->featured = $departure->featured ? 'Best Selling' : ''; 
               
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
                    // api
                    }
                return view('frontend.countries.country_departure', compact('countries', 'departures'));

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
                    $poi_array = [];
                    $poi_exi_unique = [];

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
                                $limitedDescription = implode(' ', array_slice($words, 0, 40));
                                if (count($words) > 40) {
                                    $limitedDescription .= '...';
                                }
                                $poi->description = $limitedDescription;

                                $poi->image = $poi->image 
                                    ? env('AWS_BUCKET_URL') . '/poi/' . $poi->image 
                                    : url('assets/images') . '/no_image.jpg';

                                $activity_ids = ActivityPointOfInterest::where('point_of_interest_reff_id', $poi->poiId)
                                    ->pluck('activity_id')
                                    ->toArray();

                                if ($activity_ids) {
                                    $experiences = Experience::whereIn('id', DB::table('activities')->whereIn('id', $activity_ids)->pluck('experience_id'))
                                        ->pluck('experience_name')
                                        ->toArray();
                                    $activities = DB::table('activities')
                                        ->whereIn('id', $activity_ids)
                                        ->pluck('activity_name')
                                        ->toArray();
                                    $poi->experience = $experiences;
                                    $poi->activities = $activities;
                                } else {
                                    $poi->experience = [];
                                    $poi->activities = [];
                                }

                                $poi->poi_url = "poi/" . Str::slug($poi->poi_name, '-') . "/{$poi->poiId}";
                                $poi_array[] = $poi;
                                $poi_exi_unique[] = $normalized_poi_name;
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
                                $exp->image = $exp->image 
                                    ? env('AWS_BUCKET_URL') . '/experience/' . $exp->image 
                                    : url('images') . '/no-image.jpg';
                                return $exp;
                            });
                            $experience_row = $experience_row->values(); 

                    }
                    $existing_pois = CountryExistingPoi::where('country_id', $country_id)
                        ->whereNotIn('poi_name', $poi_exi_unique)
                        ->select('poi_name', 'latitude', 'longitude', 'poi_type', 'rating', 'description', 'address', 'image')
                        ->get()
                        ->map(function ($pois_e) {
                            // Limit the description to 40 words
                            $words = explode(' ', $pois_e->description);
                            $limitedDescription = implode(' ', array_slice($words, 0, 40));
                            if (count($words) > 40) {
                                $limitedDescription .= '...';
                            }
                            $pois_e->description = $limitedDescription;

                            // Set the image URL
                            $pois_e->image = $pois_e->image 
                                ? env('AWS_BUCKET_URL') . '/poi/' . $pois_e->image 
                                : url('assets/images') . '/no_image.jpg';

                            return $pois_e;
                        });

                    return view('frontend.countries.country_attraction', compact('country_poi_detail', 'poi_array', 'experience_row', 'existing_pois'));
                }
            }
            elseif ($check_url->module_name == "country_group_page") {
            } elseif ($check_url->module_name == "country_experience_page") {
            }
            else{
                 return redirect('/404');
            }
        }
    }
}
