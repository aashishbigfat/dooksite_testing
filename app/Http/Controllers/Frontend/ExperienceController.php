<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\DestinationExperience;
use App\Models\DepartureDestination;
use App\Models\DepartureDestinationPointOfInterest;
use App\Models\LandingDeparturePage;
use App\Models\ActivityDeparture;
use App\Models\ActivityExperience;
use App\Models\Country;
use App\Models\Activity;
use App\Models\Destination;
use App\Models\Departure;
use App\Models\Inclusion;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request){
        $experience_header = LandingDeparturePage::where('type', 'landing_experience')
            ->select('title','sub_title','banner_image','description','meta_title','meta_keywords','meta_description','description')
           ->first();

        $experiences = Experience::select('id','experience_name','slug_url','image')
                ->orderBy('sorting','ASC')->where('status',1)
                ->get();
        if(!is_null($request->experience_idP)){
            $experienceId = $request->experience_idP;
        }elseif(!is_null($request->experience_idC)){
            $experienceId = $request->experience_idC;
        }else{
            $experienceId = $experiences[0]->id;
        }
        $departure_ids = DestinationExperience::join('destinations','destinations.id','=','destination_experiences.destination_id')
            ->where('destination_experiences.experience_id', $experienceId)
            ->distinct()
            ->pluck('destination_experiences.departure_id')
            ->toArray();
        $departures = Departure::select('id','title','price_currency','price','price_currency_usd','price_usd','book_online','price_hide_show','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_days','no_of_nights','image','featured','dep_type')
            ->whereIn('id',$departure_ids)
            ->where('status', 1)
            ->where('dep_type', 'package')
            ->orderBy('featured','DESC')
            ->get();

            foreach ($departures as $key => $value) {
                $capitalize = strtolower($value->title);
                $value->title = ucwords($capitalize);
                $value->dimage = $value->image;
                if($value->image != "" || $value->image != null){
                    $value->image = env('AWS_BUCKET_URL').'/package/'.$value->image;
                }else{
                    $value->image = url('images').'/package-no-image.jpg';
                }            
                $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $value->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                            $value->poi_names = $poiNames;
                $inclusions = Inclusion::where('departure_id',$value->id)
                    ->whereNotNull('icon')
                    ->select('name','icon')
                    ->distinct()
                    ->get();
                foreach ($inclusions as $key => $inc) {
                    $inc->icon =  env('AWS_BUCKET_URL'). "/inclusion/".$inc->icon;
                }
                $value->inclusions = $inclusions;
            }
             $destinatin_id = DestinationExperience::where('experience_id',$experienceId)
                    ->distinct()
                    ->pluck('destination_id')
                    ->toArray();
            $country_id = Destination::whereIn('id',$destinatin_id)
                    ->where('status', 1)
                    ->pluck('country_id')
                    ->toArray();
             $countries = Country::whereIn('id', $country_id)
                ->select('id','country_name as countryName','slug_url','image','about_country_slug_url','country_attraction_slug_url')
                ->where('status',1)
                ->get();
            if(count($countries)>0) {
                foreach ($countries as $key => $country_img) {
                    if($country_img->image != "" || $country_img->image != null){
                        $country_img->image = env('AWS_BUCKET_URL').'/country/'.$country_img->image;
                    }else{
                        $country_img->image = url('images').'/poi-no-image.jpg';
                    }
                    $country_img->about_country_slug_url = "";
                    $country_img->country_attraction_slug_url = "";

                }
            }

          return view('frontend.experiences.experiences', compact('experiences','experience_header','departures','countries'));
    }
    public function experienceDetails(Request $request,$slug)
    {
         $experience = Experience::where('slug_url', $slug)
                ->select('id','experience_name','slug_url','image','banner_image','description','sub_title','edit_Header_title','header_sub_title','meta_title','meta_keywords','exp_title','exp_sub_title','pkg_title','pkg_sub_title','country_title','country_sub_title','meta_title','meta_keywords','meta_description')
                ->where('status',1)
                ->first();
      
         $experienceId = $experience->id;

          $destinatin_id = DestinationExperience::where('experience_id',$experienceId)
                ->distinct()
                ->pluck('destination_id')
                ->toArray();
          $country_id = Destination::whereIn('id',$destinatin_id)
            ->where('status', 1)
            ->pluck('country_id')
            ->toArray();
          $countries = Country::whereIn('id', $country_id)
                ->select('id','country_name as countryName','slug_url','image','about_country_slug_url','country_attraction_slug_url')
                ->where('status',1)
                ->get();
          foreach ($countries as $key => $value) {
          if($value->image != "" || $value->image != null){
                $value->image = env('AWS_BUCKET_URL').'/country/'.$value->image;
            }else{
                $value->image = url('images').'/package-no-image.jpg';
            }  
        }

        $departure_ids = DestinationExperience::join('destinations','destinations.id','=','destination_experiences.destination_id')
            ->where('destination_experiences.experience_id', $experienceId)
            ->distinct()
            ->pluck('destination_experiences.departure_id')
            ->toArray();

        $all_destinaton_id = DepartureDestination::whereIn('departure_id',$departure_ids)
            ->distinct()
            ->pluck('destination_id')
            ->toArray();

        $departure_destination_name = Destination::whereIn('id',$all_destinaton_id)
            ->pluck('dest_name')
            ->sort()
            ->toArray();

        
            $departures = Departure::whereIn('id',$departure_ids)
                ->where('status', 1)
                ->where('dep_type', 'package')
                ->select('id','title','price_currency','price','price_currency_usd','price_usd','book_online','price_hide_show','slug_url_pre as slug1','slug_url as slug2','dep_dook_ref_id as slug3','no_of_days','no_of_nights','image','featured','dep_type')
                ->distinct()
                ->orderBy('featured','DESC')
                ->paginate(8);
            $packages_count = Departure::whereIn('id',$departure_ids)
                ->where('status', 1)
                ->select('id')
                ->distinct()
                ->get(); 

        foreach ($departures as $key => $value) {
            $capitalize = strtolower($value->title);
            $value->title = ucwords($capitalize);
            $value->dimage = $value->image;
            if($value->image != "" || $value->image != null){
                $value->image = env('AWS_BUCKET_URL').'/package/'.$value->image;
            }else{
                $value->image = url('images').'/package-no-image.jpg';
            }            
            $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $value->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                        $value->poi_names = $poiNames;
            $inclusions = Inclusion::where('departure_id',$value->id)
                ->whereNotNull('icon')
                ->select('name','icon')
                ->distinct()
                ->get();
            foreach ($inclusions as $key => $inc) {
                $inc->icon =  env('AWS_BUCKET_URL'). "/inclusion/".$inc->icon;
            }
            $value->inclusions = $inclusions;
        }
        $departure_id = DestinationExperience::where('experience_id',$experience->id)
            ->distinct()
            ->pluck('departure_id')
            ->toArray();
       
        $package_wise_activity_id = ActivityDeparture::whereIn('departure_id', $departure_id)
                ->distinct()
                ->pluck('activity_id')
                ->toArray();
        $all_activities = ActivityExperience::where('experience_id',$experience->id)
                ->distinct()
                ->pluck('activity_id')
                ->toArray();
        $activity_id_array = array_intersect($package_wise_activity_id, $all_activities);
       
        $activities = Activity::whereIn('id', $activity_id_array)
                ->select('id','activity_name','slug_url','image')
                ->where('status', 1)
                ->get();
        if(count($activities)>0){
            foreach ($activities as $key => $value) {
                $departure_id_row = ActivityDeparture::where('activity_id',$value->id)
                        ->distinct()
                        ->pluck('departure_id')
                        ->toArray();
                if($value->image != "" || $value->image != null){
                $value->image = env('AWS_BUCKET_URL').'/activities/'.$value->image;
                }else{
                    $value->image = url('images').'/event-no-image.jpg';
                }
                $value->total_departure = count($departure_id_row);
                $destintion_id_row = DepartureDestination::whereIn('departure_id',$departure_id_row)
                        ->distinct()
                        ->pluck('destination_id')
                        ->toArray();
                         $value->total_destination = count($destintion_id_row);             
            }
        }

        return view('frontend.experiences.experience_detail', compact('experience','departures','activities','countries'));
    }
}
