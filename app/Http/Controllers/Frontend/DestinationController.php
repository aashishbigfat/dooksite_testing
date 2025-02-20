<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LandingDeparturePage;
use App\Models\TopDestination;
use App\Models\Destination;
use App\Models\DepartureDestination;
use App\Models\DestinationExperience;
use App\Models\Experience;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $destination_header= LandingDeparturePage::where('type', 'landing_destination')
            ->select('title', 'sub_title', 'meta_title', 'meta_keywords', 'meta_description', 'description')
            ->first();
   
        $top_destinations = TopDestination::where(['status' => 1, 'type' => 'departures'])
            ->orderBy('grid_number', 'ASC')
            ->select('id', 'destination_id', 'label_name', 'image', 'grid_number')
            ->paginate(12);
    
        foreach ($top_destinations as $topDestination) {
            $depName = Destination::where('id', $topDestination->destination_id)
                ->select('id', 'dest_name', 'slug_url')
                ->first();
    
            $topDestination->destination_name = $depName ? $depName->dest_name : '';
            $topDestination->slug_url = $depName ? $depName->slug_url : '';
            $topDestination->total_dep = $depName
                ? DepartureDestination::where('destination_id', $depName->id)
                    ->distinct('departure_id')
                    ->count('departure_id')
                : 0;
    
            $experience = TopDestination::where('id', $topDestination->id)
                ->select('experience_name')
                ->first();
           $topDestination->experiences = $experience
            ? explode(',', $experience->experience_name) // Split the experiences into an array
            : [];

            $topDestination->image = env('AWS_BUCKET_URL') . '/destinations/' . $topDestination->image;
        }

         $remainingDestinationsIds = TopDestination::where(['status' => 1, 'type' => 'departures'])
            ->pluck('destination_id')
            ->toArray();

        $destinations = Destination::whereNotIn('id', $remainingDestinationsIds)
            ->where('status', 1)
            ->where('slug_url', '!=', '')
            ->select('id', 'dest_name', 'slug_url', 'image')
            ->paginate(12);

        $destinations->getCollection()->transform(function ($destination) {

            $destination->total_departure = DepartureDestination::where('destination_id', $destination->id)
                ->distinct('departure_id')
                ->count('departure_id');

            // Get the experiences
            $experienceIds = DestinationExperience::where('destination_id', $destination->id)
                ->distinct()
                ->pluck('experience_id')
                ->toArray();

            $experienceNames = Experience::whereIn('id', $experienceIds)
                ->where('status', 1)
                ->limit(4)
                ->distinct()
                ->pluck('experience_name')
                ->toArray();

                $destination->experiences = $experienceNames ? $experienceNames : [];

            $destination->image = $destination->image
                ? env('AWS_BUCKET_URL') . '/poi/' . $destination->image
                : url('images') . '/poi-no-image.jpg';

            return $destination;
        });

        $destinationData = $destinations;

            return view('frontend.destination.destination', compact('destination_header', 'top_destinations','destinationData'));
    }
    public function destinationDetail(Request $request,$slug){
        
    }

}
