<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
    public function hotelCategories()
    {
        return $this->hasMany(HotelCategory::class);
    }
    public function inclusions()
    {
        return $this->hasMany(Inclusion::class);
    }
    public function countryDepartures()
    {
        return $this->belongsTo(Country::class);
    }
}
