<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DepartureDestinationPointOfInterest;
use App\Models\ActivityPointOfInterest;
use App\Models\HotelCategory;
use App\Models\Destination;
use App\Models\Inclusion;
use App\Models\Departure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PoiController extends Controller
{
   public function poi_details(Request $request, $slug, $id)
    {
        $poi_check = DepartureDestinationPointOfInterest::where('reference_id', $id)->select('poi_name')->first();
        if (!$poi_check || !isset($poi_check->poi_name)) {
            return redirect('/404');
        }
        $poi_url = $this->generatePoiUrl($poi_check->poi_name);
        if ($poi_url != $slug) {
            return redirect('/404');
        }
        $poiID = $this->getPoiDetails($id);
        if (!$poiID) {
            return redirect('/404');
        }
        $destination_country_names = $this->getDestinationCountry($poiID->destination_id);
        $poiID->destination_name = $destination_country_names->dest_name;
        $poiID->country_name = $destination_country_names->country_name;
         if ($poiID->image) {
            $poiID->image = env('AWS_BUCKET_URL') . '/poi/' . $poiID->image;
        } else {
            $poiID->image = url('images') . '/package-no-image.jpg';
        }
        $activity_ids = ActivityPointOfInterest::where('point_of_interest_reff_id', $poiID->poiId)
            ->pluck('activity_id')
            ->toArray();
        $poiNames = $this->sanitizePoiName($poiID->poi_name);
        $poiID->meta_title = "Explore {$poiNames}, {$poiID->destination_name} with Dook";
        $poiID->meta_description = "Explore {$poiNames}, {$poiID->destination_name} with Dook. Check out Top Tour Packages Featuring {$poiNames} and Nearby Attractions!";
        $poiID->meta_keywords = "";
        $departures = $this->getDepartures($poiID->poiId);
        $related_pois = $this->getRelatedPois($poiID->destination_id, $poiID->poiId);

        return view('frontend.poi.poi_detail', compact('poiID', 'departures', 'related_pois'));
    }
    private function generatePoiUrl($poi_name)
    {
        $make_poi_url = str_replace(
            ['\'', '"', ',', ';', '<', '>', '&', '$', '(', ')', '}', '{', '[', ']', '%', '+', '_', '.', '^', '#', '@', '*', '’'],
            '',
            $poi_name
        );

        $strlower = Str::lower($make_poi_url);
        $arr = explode(' ', $strlower);
        $str = implode('-', $arr);
        return str_replace(['--', '---', '----'], '-', $str);
    }
    private function getPoiDetails($id)
    {
        return DepartureDestinationPointOfInterest::where('reference_id', $id)
            ->select('reference_id as poiId', 'destination_id', 'poi_name', 'latitude', 'longitude', 'poi_type', 'phone', 'website', 'rating', 'openhours', 'description', 'height as elevation', 'address', 'image', 'banner_image')
            ->first();
    }
    private function getDestinationCountry($destination_id)
    {
        return Destination::where('id', $destination_id)
            ->select('dest_name', 'country_name')
            ->first();
    }
    private function sanitizePoiName($poi_name)
    {
        return str_replace(
            ['\'', '"', ',', ';', '<', '>', '&', '$', '(', ')', '}', '{', '[', ']', '%', '+', '_', '.', '^', '#', '@', '*', '’', '?'],
            '',
            $poi_name
        );
    }
    private function getDepartures($poiId)
    {
        $package_ids = DepartureDestinationPointOfInterest::where('reference_id', $poiId)
            ->pluck('departure_id')
            ->toArray();
        $departures = Departure::whereIn('id', $package_ids)
            ->where('status', 1)
            ->select('id', 'title', 'price_currency', 'price', 'price_currency_usd', 'price_usd', 'book_online', 'price_hide_show', 'no_of_days', 'no_of_nights', 'slug_url_pre as slug1', 'slug_url as slug2', 'dep_dook_ref_id as slug3', 'dep_type', 'image', 'featured')
            ->paginate(3);

        $departures->getCollection()->map(function ($departure) {
            $this->processDepartureImage($departure);
            return $departure;
        });
        return $departures;
    }

    private function processDepartureImage($departure)
    {
        $departure->title = ucwords(strtolower($departure->title));
        $departure->dimage = $departure->image;

        if ($departure->image) {
            $departure->image = env('AWS_BUCKET_URL') . '/package/' . $departure->image;
        } else {
            $departure->image = url('images') . '/package-no-image.jpg';
        }

         $poiNames = DepartureDestinationPointOfInterest::where('departure_id', $departure->id)->where('status', 1)->limit(4)->distinct()->pluck('poi_name')->toArray();
                        $departure->poi_names = $poiNames;
        $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';
        $prices = HotelCategory::where('departure_id', $departure->id)
            ->orderBy('price_inr', 'ASC')
            ->first();
        if ($prices) {
            $departure->price = $prices->price_inr;
            $departure->price_usd = $prices->price_usd;
        }

        $inclusions = Inclusion::where('departure_id', $departure->id)
            ->whereNotNull('icon')
            ->select('name', 'icon')
            ->distinct()
            ->get()
            ->map(function ($inc) {
                $inc->icon = env('AWS_BUCKET_URL') . "/inclusion/" . $inc->icon;
                return $inc;
            });
        $departure->inclusions = $inclusions;
    }

    private function getRelatedPois($destination_id, $poiId)
    {
        return DepartureDestinationPointOfInterest::where('destination_id', $destination_id)
            ->where('reference_id', '!=', $poiId)
            ->where('status', 1)
            ->select('destination_id', 'reference_id as poiId', 'poi_name', 'image', 'description')
            ->distinct('destination_id')
            ->limit(8)
            ->get()
            ->map(function ($poi) use ($destination_id) {
                $this->processRelatedPoi($poi, $destination_id);
                return $poi;
            });
    }
    private function processRelatedPoi($poi, $destination_id)
    {
        $total_departures = DepartureDestinationPointOfInterest::where('reference_id', $poi->poiId)
            ->select('departure_id')
            ->get();
        $poi->total_departures = count($total_departures);
        $featured_array = [];
        foreach ($total_departures as $departure) {
            $featured_row = Departure::where('id', $departure->departure_id)
                ->where('featured', 1)
                ->select('id')
                ->first();
            if ($featured_row) {
                $featured_array[] = $featured_row->id;
            }
        }
        $poi->featured_departure = count($featured_array);
        $poi->poi_url = $this->generatePoiUrl($poi->poi_name);
        if ($poi->image) {
            $poi->image = env('AWS_BUCKET_URL') . '/poi/' . $poi->image;
        } else {
            $poi->image = url('images') . '/poi-no-image.jpg';
        }
    }

}
