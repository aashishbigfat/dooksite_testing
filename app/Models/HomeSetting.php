<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    public function experinceOne() {
        return $this->belongsTo(Experience::class, 'experience1', 'id')
                    ->with(['destinationExperiences' => function($query) {
                        $query->join('departures', 'departures.id', '=', 'destination_experiences.departure_id')
                            ->where('departures.status', 1)
                            ->whereNotNull('departures.price')
                            ->orderBy('departures.price', 'ASC');
                    }]);
    }

    public function experinceTwo() {
        return $this->belongsTo(Experience::class, 'experience2', 'id')
                    ->with(['destinationExperiences' => function($query) {
                        $query->join('departures', 'departures.id', '=', 'destination_experiences.departure_id')
                            ->where('departures.status', 1)
                            ->whereNotNull('departures.price')
                            ->orderBy('departures.price', 'ASC');
                    }]);
    }

    public function experinceThree() {
        return $this->belongsTo(Experience::class, 'experience3', 'id')
                    ->with(['destinationExperiences' => function($query) {
                        $query->join('departures', 'departures.id', '=', 'destination_experiences.departure_id')
                            ->where('departures.status', 1)
                            ->whereNotNull('departures.price')
                            ->orderBy('departures.price', 'ASC');
                    }]);
    }

    public function experinceFour() {
        return $this->belongsTo(Experience::class, 'experience4', 'id')
                    ->with(['destinationExperiences' => function($query) {
                        $query->join('departures', 'departures.id', '=', 'destination_experiences.departure_id')
                            ->where('departures.status', 1)
                            ->whereNotNull('departures.price')
                            ->orderBy('departures.price', 'ASC');
                    }]);
    }

    public function experinceFive() {
        return $this->belongsTo(Experience::class, 'experience5', 'id')
                    ->with(['destinationExperiences' => function($query) {
                        $query->join('departures', 'departures.id', '=', 'destination_experiences.departure_id')
                            ->where('departures.status', 1)
                            ->whereNotNull('departures.price')
                            ->orderBy('departures.price', 'ASC');
                    }]);
    }
}

