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
use App\Models\RegionCountry;
use App\Models\RegionExperience;
use Illuminate\Http\Request;

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
                $country_value->image = env('AWS_BUCKET_URL').'/region/'.$country_value->image;
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
            $departure->image = $departure->image ? env('AWS_BUCKET_URL').'/package/'.$departure->image : url('images').'/package-no-image.jpg';
            $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';

            $inclusions = Inclusion::where('departure_id', $departure->id)
                                   ->whereNotNull('icon')
                                   ->select('name', 'icon')
                                   ->distinct()
                                   ->get();

            foreach ($inclusions as $inclusion) {
                $inclusion->icon = env('AWS_BUCKET_URL')."/inclusion/".$inclusion->icon;
            }

            $departure->inclusions = $inclusions;
        }

        $region_picklist = Region::select('id', 'grid_number', 'region_name', 'label_name', 'slug_url', 'image')
                                 ->orderBy('grid_number', 'ASC')
                                 ->get();

        return view('frontend.regions.region', compact('region_header', 'regions', 'departures', 'region_picklist'));
    }
    public function regionDetails(Request $request,$slug)
    {
        return view('frontend.regions.regiondetail');
    }
}
