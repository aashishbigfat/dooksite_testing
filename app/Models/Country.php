<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
   public function departures()
{
    return $this->belongsToMany(Departure::class, 'country_departures', 'country_id', 'departure_id');
}
public function bestTimeToVisits()
{
    return $this->hasMany(CountryBestTimeToVisit::class, 'country_id');
}

public function climateTypes()
{
    return $this->hasMany(CountryClimateType::class, 'country_id');
}

}
