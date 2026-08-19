<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionCountry extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
     public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
