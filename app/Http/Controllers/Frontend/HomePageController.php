<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\Country;
use App\Models\Experience;
use App\Models\HomeSetting;
use App\Models\DookSpecial;
use App\Models\MegaMenuDestination;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use DB;

class HomePageController extends Controller
{
    public function index() {
        $header = [
            "Username"=>env('AGENT_CONNECT_USERNAME'),
            "Password"=>env('AGENT_CONNECT_PASSWORD')
        ];
        $apiUrl = env('AGENT_CONNECT_API_BASE_URL')."/departure/group-departure";
        try{
            $toursResponse =  Cache::remember('group-tours',86400, function () use($apiUrl,$header){
                $response= Http::retry(3, 100)->withHeaders($header)->get($apiUrl);
                $res = $response->getBody()->getContents();
                return  json_decode($res,true);
            });
        }catch (Exception $e) {
            Log::info("Api Error:-",['message'=>$e->getMessage(),"Api Url"=>$apiUrl]);
        }
        $groupTours = $toursResponse !=null?$toursResponse['Result']:null;
        if ($groupTours) {
            $bestSellingTours = collect($groupTours)->filter(function ($tour) {
                return isset($tour['BestSellingPackage']) && $tour['BestSellingPackage'] === true;
            });        
            $otherTours = collect($groupTours)->filter(function ($tour) {
                return !(isset($tour['BestSellingPackage']) && $tour['BestSellingPackage'] === true);
            });        
            $groupTours = $bestSellingTours->merge($otherTours)->take(10)->all();
        }
        $departures = Departure::where(['dep_type'=>'package','status'=>1]) ->orderByDesc('featured')->orderBy('popular_at_home', 'DESC')->take(7)->get();
        $homeSettings = HomeSetting::with(['experinceOne','experinceTwo','experinceThree','experinceFour','experinceFive'])->first();
        $topDestinations = MegaMenuDestination::orderBy('order','ASC')->with(['destination' => function ($query) {
            $query->where('status','1');
        },'departureDestination'=>fn($q)=>$q->distinct()])->take(6)->get();
        $countries = DB::table('footer_countries')
        ->join('countries','countries.id','=','footer_countries.country_id')
        ->select('countries.country_name as name','countries.slug_url as slug')
        ->orderBy('footer_countries.orders','ASC')
        ->get();
        $experiences = DB::table('experiences')
        ->where('status',1)
        ->where('slug_url','!=','')
        ->select('experience_name as name','slug_url as slug')
        ->get()->toArray();
        $dook_special = DookSpecial::where('status',1)->limit(4)->get();
        $destinations = DB::table('footer_destinations')
        ->join('destinations','destinations.id','=','footer_destinations.destination_id')
        ->select('destinations.dest_name as name','destinations.slug_url as slug')
        ->orderBy('footer_destinations.orders','ASC')
        ->get();
        return view('frontend.index',compact('groupTours','departures','homeSettings','topDestinations','countries','experiences','destinations','dook_special'));
    }
}
