<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingDeparturePage;
use App\Models\ActivityDeparture;
use App\Models\Activity;
use App\Models\Departure;
use App\Models\Inclusion;
use App\Models\Country;
use App\Models\Destination;
use App\Models\CountryDeparture;
use App\Models\DepartureDestination;
use App\Models\DepartureDestinationPointOfInterest;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request){
        $activity_header = LandingDeparturePage::where('type', 'landing_activity')
            ->select('title','sub_title','banner_image','description','meta_title','meta_keywords','meta_description','description')
           ->first();

        $activities = Activity::select('id','activity_name','slug_url','image')
                ->orderBy('activity_name','ASC')->where('status',1)
                ->where('slug_url','!=',"")
                ->paginate(9); 
        $activity =  $activities; 
        if(count($activities)>0){  
            foreach ($activities as $key => $value) { 
                if($value->image != "" || $value->image != null){
                    $value->image = env('AWS_BUCKET_URL').'/activities/'.$value->image;
                }else{
                    $value->image = url('images').'/event-no-image.jpg';
                }
                $departure_id_row = ActivityDeparture::where('activity_id',$value->id)
                        ->distinct()
                        ->pluck('departure_id')
                        ->toArray();
                $value->total_departure = count($departure_id_row);
                $destintion_id_row = DepartureDestination::whereIn('departure_id',$departure_id_row)
                        ->distinct()
                        ->pluck('destination_id')
                        ->toArray();
                $value->total_destination = count($destintion_id_row);
            }
        }
         if(!is_null($request->activity_idP)){
            $activityId = $request->activity_idP;
        }elseif(!is_null($request->activity_idC)){
            $activityId = $request->activity_idC;
        }else{
            $activityId = $activities[0]->id;
        }
        $departure_ids = ActivityDeparture::where('activity_id', $activityId)
                ->distinct()
                ->pluck('departure_id')
                ->toArray();
        $departures = Departure::select('id','title','price_currency','price','price_currency_usd','price_usd','book_online','price_hide_show','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_days','no_of_nights','image','featured','dep_type')
            ->whereIn('id',$departure_ids)
            ->where('status', 1)
            ->orderBy('featured','DESC')
            ->paginate(8);
            foreach ($departures as $key => $value) {
                 $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $value->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                 $value->poi_names = $poiNames;
                $capitalize = strtolower($value->title);
                $value->title = ucwords($capitalize);
                $value->dimage = $value->image;
                if($value->image != "" || $value->image != null){
                    $value->image = env('AWS_BUCKET_URL').'/package/'.$value->image;
                }else{
                    $value->image = url('images').'/package_default_img.jpg';
                }
                
                
                $value->featured = $value->featured == 1?'Best Selling':'';
                $inclusions = Inclusion::where('departure_id',$value->id)
                    ->whereNotNull('icon')
                    ->select('name','icon')
                    ->distinct()
                    ->get();
                foreach ($inclusions as $key => $inc) {
                    $inc->icon = env('AWS_BUCKET_URL')."/inclusion/".$inc->icon;
                }
                $value->inclusions = $inclusions;
            }
             $departure_ids = ActivityDeparture::where('activity_id', $activityId)
                ->distinct()
                ->pluck('departure_id')
                ->toArray();
            $country_id = CountryDeparture::whereIn('departure_id',$departure_ids)
                    ->distinct()
                    ->pluck('country_id')
                    ->toArray();

            $countries = Country::whereIn('id', $country_id)
                ->select('id','country_name as countryName','slug_url','image','about_country_slug_url','country_attraction_slug_url')
                ->where('status',1)
                ->get();
            if(count($countries)>0) {
                foreach ($countries as $key => $country_img) {
                    $country_img->about_country_slug_url = "";
                    $country_img->country_attraction_slug_url = "";
                }
        } 
       

          return view('frontend.activity.index', compact('activities','activity_header','departures','countries'));

    }
   public function activityDetails(Request $request, $slug_url)
    {
        $activity = Activity::where('slug_url', $slug_url)
                            ->select('id', 'activity_name', 'slug_url', 'image', 'banner_image', 'header_title', 
                                     'header_sub_title', 'meta_title', 'meta_keywords', 'meta_description', 'description')
                            ->where('status', 1)
                            ->first();
        if (!$activity) {
            return redirect('/404');
        }
        $activity->meta_title = $activity->meta_title ?: '20+ Best ' . $activity->activity_name . ' Packages Worldwide @ Budget Price';
        $activity->meta_description = $activity->meta_description ?: $activity->activity_name . ' Tours - Explore 20+ ' . $activity->activity_name . ' Packages around the World. Book ' . $activity->activity_name . ' online at a budget price only at Dook!';
        $activity->header_title = $activity->header_title ?: 'Amusement Park';
        $departure_ids = ActivityDeparture::where('activity_id', $activity->id)
                                           ->distinct()
                                           ->pluck('departure_id')
                                           ->toArray();
        $all_destinaton_id = DepartureDestination::whereIn('departure_id', $departure_ids)
                                                  ->distinct()
                                                  ->pluck('destination_id')
                                                  ->toArray();
        $departure_destination_name = Destination::whereIn('id', $all_destinaton_id)
                                                  ->pluck('dest_name')
                                                  ->sort()
                                                  ->toArray();
        $departures = Departure::select('id', 'title', 'price_currency', 'price', 'price_currency_usd', 'price_usd', 
                                        'book_online', 'price_hide_show', 'slug_url_pre as slug1', 'slug_url as slug2', 
                                        'dep_dook_ref_id as slug3', 'no_of_days', 'no_of_nights', 'image', 'featured', 'dep_type')
                                ->whereIn('id', $departure_ids)
                                ->where('status', 1)
                                ->orderBy('featured', 'DESC')
                                ->paginate(6);
        foreach ($departures as $departure) {
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
            $departure->poi_names = $poiNames;
            $departure->title = ucwords(strtolower($departure->title));
            $departure->dimage = $departure->image;
             $departure->image = $departure->image ? env('AWS_BUCKET_URL') . '/package/' . $departure->image : url('images') . '/package-no-image.jpg';
            $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';

            $inclusions = Inclusion::where('departure_id', $departure->id)
                                   ->whereNotNull('icon')
                                   ->select('name', 'icon')
                                   ->distinct()
                                   ->get();
            foreach ($inclusions as $inc) {
                $inc->icon = env('AWS_BUCKET_URL') . "/inclusion/" . $inc->icon;
            }
            $departure->inclusions = $inclusions;
        }
        $country_id = CountryDeparture::whereIn('departure_id', $departure_ids)
                                      ->distinct()
                                      ->pluck('country_id')
                                      ->toArray();
        $countries = Country::whereIn('id', $country_id)
                            ->select('id', 'country_name as countryName', 'slug_url', 'image', 
                                     'about_country_slug_url', 'country_attraction_slug_url')
                            ->where('status', 1)
                            ->paginate(12);
        foreach ($countries as $country) {
            $country->image = $country->image ?: url('images') . '/package-no-image.jpg';
            $country->about_country_slug_url = '';
            $country->country_attraction_slug_url = '';
        }
        $countries_count = Country::whereIn('id', $country_id)
                                  ->where('status', 1)
                                  ->select('id', 'country_name as countryName', 'country_experience_slug_url as exp_slug', 
                                           'slug_url', 'image')
                                  ->get();

        return view('frontend.activity.activity_detail', compact('activity', 'departures', 'countries', 'departure_destination_name'));
    }

}
