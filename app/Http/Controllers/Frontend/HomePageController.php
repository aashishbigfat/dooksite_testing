<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\HomeSetting;
use App\Models\MegaMenuDestination;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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
        shuffle($groupTours);
        $departures = Departure::where(['dep_type'=>'package','status'=>1,'popular_at_home'=>1])->take(7)->get();
        $homeSettings = HomeSetting::with(['experinceOne','experinceTwo','experinceThree','experinceFour'])->first();
        $topDestinations = MegaMenuDestination::orderBy('order','ASC')->with(['destination' => function ($query) {
            $query->where('status','1');
        },'departureDestination'=>fn($q)=>$q->distinct()])->take(6)->get();
        return view('frontend.index',compact('groupTours','departures','homeSettings','topDestinations'));
    }
}
