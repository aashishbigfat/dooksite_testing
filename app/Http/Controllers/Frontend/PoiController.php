<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DepartureDestinationPointOfInterest;
use App\Models\ActivityPointOfInterest;
use App\Models\Destination;
use App\Models\Departure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PoiController extends Controller
{
   public function poi_details(Request $request, $slug, $id)
    {
        $poi_check = DepartureDestinationPointOfInterest::where('reference_id', $id)->select('poi_name')->first();
        if (!$poi_check || !isset($poi_check->poi_name)) {
            return redirect('/');
        }
        $poi_url = $this->generatePoiUrl($poi_check->poi_name);
        if ($poi_url != $slug) {
            return redirect('/');
        }
        $poiID = $this->getPoiDetails($id);
        if (!$poiID) {
            return redirect('/');
        }
        $destination_country_names = $this->getDestinationCountry($poiID->destination_id);
        $poiID->destination_name = $destination_country_names->dest_name;
        $poiID->country_name = $destination_country_names->country_name;
         if ($poiID->image) {
            $poiID->image = generateSignedUrl('poi/' . $poiID->image);
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
         if ($request->ajax()) {
               return response()->json([
                   'view' => view('frontend.common.tourpackage', compact('departures'))->render(),
                   'hasMorePages' => $departures->hasMorePages()
               ]);
           }

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
            ->paginate(6);

        // Names, prices, inclusions and country for every card on this page at
        // once, instead of six queries per departure inside the loop below.
        $cards = departureCardData($departures->getCollection()->pluck('id')->all());

        $departures->getCollection()->map(function ($departure) use ($cards) {
            $this->processDepartureImage($departure, $cards[$departure->id] ?? []);
            return $departure;
        });
        return $departures;
    }

    private function processDepartureImage($departure, array $card = [])
    {
        $departure->title = ucwords(strtolower($departure->title));
        $departure->dimage = $departure->image;
         $departure->no_of_nights = "{$departure->no_of_days} Days {$departure->no_of_nights} Nights";
         $departure->colMd = "col-md-4 col-6";
        if ($departure->image) {
            $departure->image = generateSignedUrl('package/' . $departure->image);
        } else {
            $departure->image = url('images') . '/package-no-image.jpg';
        }

        $departure->poi_names = $card['poi_names'] ?? [];
        $departure->featured = $departure->featured == 1 ? 'Best Selling' : '';
        // Present but empty means the departure has a hotel category whose
        // price is blank; absent means it has none at all, and the departure's
        // own price stands - the same distinction ->first() drew before.
        if (isset($card['price'])) {
            $departure->price = $card['price']['inr'];
            $departure->price_usd = $card['price']['usd'];
        }

        // Signed at render time rather than cached: a signed URL expires.
        $departure->inclusions = collect($card['inclusions'] ?? [])
            ->map(function ($inc) {
                return (object) [
                    'name' => $inc['name'],
                    'icon' => generateSignedUrl('inclusion/' . $inc['icon']),
                ];
            });

        $departure->country_name = $card['country_name'] ?? null;
    }

    private function getRelatedPois($destination_id, $poiId)
    {
        $pois = DepartureDestinationPointOfInterest::where('destination_id', $destination_id)
            ->where('reference_id', '!=', $poiId)
            ->where('status', 1)
            ->select('destination_id', 'reference_id as poiId', 'poi_name', 'image', 'description')
            ->distinct('destination_id')
            ->get()
            // One card per attraction. The same POI is linked once per departure
            // and often carries a different image on each of those rows, so
            // SELECT DISTINCT sees them as distinct and cannot collapse them.
            ->unique('poiId')
            ->values();

        // Counted for the whole set at once (cached per POI) instead of two
        // queries per POI inside the map below.
        $counts = poiDepartureCounts($pois->pluck('poiId')->all());

        return $pois->map(function ($poi) use ($counts) {
            $this->processRelatedPoi($poi, $counts);
            return $poi;
        });
    }
    private function processRelatedPoi($poi, array $counts)
{
    // TOTAL ACTIVE DEPARTURES / FEATURED PACKAGE DEPARTURES
    $poi->total_departures = $counts[$poi->poiId]['total'] ?? 0;
    $poi->featured_departure = $counts[$poi->poiId]['featured'] ?? 0;

    // POI URL
    $poi->poi_url = $this->generatePoiUrl($poi->poi_name);

    // IMAGE
    if (!empty($poi->image)) {
        $poi->image = generateSignedUrl('poi/' . $poi->image);
    } else {
        $poi->image = url('images') . '/poi-no-image.jpg';
    }
}


}
