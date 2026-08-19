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
use DB;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activity_header = LandingDeparturePage::where('type', 'landing_activity')
            ->select('title','sub_title','banner_image','description','meta_title','meta_keywords','meta_description','description')
           ->first();

        $activities = Activity::select('id','activity_name','slug_url','image')
                ->orderBy('activity_name','ASC')->where('status',1)
                ->where('slug_url','!=',"")
                ->paginate(9);
        if(count($activities)>0){ 
            foreach ($activities as $key => $value) {
                $value->colMd = "col-md-4 col-6";
                if($value->image != "" || $value->image != null){
                    $value->image = generateSignedUrl('activities/'.$value->image);
                }else{
                    $value->image = url('images').'/event-no-image.jpg';
                }
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
             ->where('dep_type', 'package')
            ->where('status', 1)
            ->orderBy('featured','DESC')
            ->paginate(8);

            foreach ($departures as $departure) {
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
            $departure->poi_names = $poiNames;
            $departure->title = ucwords(strtolower($departure->title));
            $departure->dimage = $departure->image;
            $departure->image = $departure->image ? generateSignedUrl('package/'.$departure->image) : url('images').'/package-no-image.jpg';
            $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';
            $departure->colMd = "col-md-3 col-12";
              $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
             $inclusions = Inclusion::join('icon_inclusions', 'inclusions.icon_inclusion_id', '=', 'icon_inclusions.id')
                       ->where('inclusions.departure_id', $departure->id)
                       ->whereNotNull('icon_inclusions.icon_old')
                       ->select('icon_inclusions.name', 'icon_inclusions.icon') // Select name and icon from icon_inclusions
                       // ->distinct()
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
            ->paginate(4);
        if(count($countries)>0) {
                    foreach ($countries as $key => $country_img) {
                        if($country_img->image != "" || $country_img->image != null){
                            $country_img->image = generateSignedUrl('country/'.$country_img->image);
                        }else{
                            $country_img->image = url('images').'/poi-no-image.jpg';
                        }
                        $country_img->about_country_slug_url = "";
                        $country_img->country_attraction_slug_url = "";

                    }
                }
         if ($request->ajax()) {
            return response()->json([
                'activities' => view('frontend.common.activity_card', compact('activities'))->render(),
                'departures' => view('frontend.common.tourpackage', compact('departures'))->render(),
                'countries' => view('frontend.countries.countries_card', compact('countries'))->render(),
                'hasMoreActivities' => $activities->hasMorePages(),
                'hasMoreDepartures' => $departures->hasMorePages(),
                'hasMoreCountries' => $countries->hasMorePages(),

            ]);
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
            return redirect('/');
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
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->where('dep_type', 'package')->limit(4)->distinct()->pluck('poi_name')->toArray();
            $departure->poi_names = $poiNames;
            $departure->title = ucwords(strtolower($departure->title));
            $departure->dimage = $departure->image;
             $departure->image = $departure->image ? generateSignedUrl('package/' . $departure->image) : url('images') . '/package-no-image.jpg';
            $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';
             $departure->colMd = "col-md-4 col-12";
              $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
            $inclusions = Inclusion::join('icon_inclusions', 'inclusions.icon_inclusion_id', '=', 'icon_inclusions.id')
                       ->where('inclusions.departure_id', $departure->id)
                       ->whereNotNull('icon_inclusions.icon_old')
                       ->select('icon_inclusions.name', 'icon_inclusions.icon') // Select name and icon from icon_inclusions
                       // ->distinct()
                       ->get();
            foreach ($inclusions as $inc) {
                $inc->icon = generateSignedUrl("inclusion/" . $inc->icon);
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
        $country_id = CountryDeparture::whereIn('departure_id', $departure_ids)
                                      ->distinct()
                                      ->pluck('country_id')
                                      ->toArray();
        $countries = Country::whereIn('id', $country_id)
                            ->select('id', 'country_name as countryName', 'slug_url', 'image', 
                                     'about_country_slug_url', 'country_attraction_slug_url')
                            ->where('status', 1)
                            ->paginate(12);
            if(count($countries)>0) {
                    foreach ($countries as $key => $country_img) {
                        if($country_img->image != "" || $country_img->image != null){
                            $country_img->image = generateSignedUrl('country/'.$country_img->image);
                        }else{
                            $country_img->image = url('images').'/poi-no-image.jpg';
                        }
                        $country_img->about_country_slug_url = "";
                        $country_img->country_attraction_slug_url = "";

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
        
        return view('frontend.activity.activity_detail', compact('activity', 'departures', 'countries', 'departure_destination_name'));
    }

}
