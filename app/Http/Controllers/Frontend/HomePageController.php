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
use App\Models\Inclusion;
use App\Models\MegaMenuDestination;
use App\Models\DepartureDestinationPointOfInterest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use DB;

class HomePageController extends Controller
{
    public function index() {

       $departures = Departure::where([
        'dep_type' => 'package',
        'status' => 1,
        'popular_at_home' => 1
        ])
        ->orderByDesc('featured')
        ->orderBy('popular_at_home', 'DESC')
        ->take(8)
        ->get()
        ->map(function ($departure) {

        $departure->duration = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";

        // Fetch POIs
        $departure->poi_names = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)
            ->where('status', 1)
            ->limit(4)
            ->distinct()
            ->pluck('poi_name')
            ->toArray();

        // Fetch inclusions
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

        $departure->inclusions = $inclusions;

        // Get destination_id from departure_destinations
        $destinationId = DB::table('departure_destinations')
            ->where('departure_id', $departure->id)
            ->value('destination_id');

        // Log::info("Departure ID: {$departure->id} => Destination ID: " . ($destinationId ?? 'null'));

        $departure->country_name = null; // default

        if ($destinationId) {
            // Get country_id from destinations
            $countryId = DB::table('destinations')
                ->where('id', $destinationId)
                ->value('country_id');

            // Log::info("Destination ID: {$destinationId} => Country ID: " . ($countryId ?? 'null'));

            if ($countryId) {
                // Get country_name from countries
                $countryName = DB::table('countries')
                    ->where('id', $countryId)
                    ->value('country_name');

                // Log::info("Country ID: {$countryId} => Country Name: " . ($countryName ?? 'null'));

                $departure->country_name = $countryName ?: null;
            }
        }

        return $departure;
    });

        $homeSettings = HomeSetting::with(['experinceOne','experinceTwo','experinceThree','experinceFour','experinceFive'])->first(); 
    
        $experienceOnePrices = $homeSettings->experinceOne->destinationExperiences->pluck('price')->sort()->first();
        $experiencetwoPrices = $homeSettings->experinceTwo->destinationExperiences->pluck('price')->sort()->first();
        $experiencethreePrices = $homeSettings->experinceThree->destinationExperiences->pluck('price')->sort()->first();
        $experiencefourPrices = $homeSettings->experinceFour->destinationExperiences->pluck('price')->sort()->first();
        $experiencefivePrices = $homeSettings->experinceFive->destinationExperiences->pluck('price')->sort()->first();

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
        return view('frontend.index',compact('departures','homeSettings','topDestinations','countries','experiences','destinations','dook_special','experienceOnePrices','experiencetwoPrices','experiencethreePrices','experiencefourPrices','experiencefivePrices'));
    }
}
