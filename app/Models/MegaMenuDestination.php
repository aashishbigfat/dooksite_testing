<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MegaMenuDestination extends Model
{
    public function destination() {
        return $this->belongsTo(Destination::class,'destination_id','id');
    }

    public function departureDestination() {
        return $this->hasMany(DepartureDestination::class,'destination_id','destination_id');
    }

}
