<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartureDestination extends Model
{
    public function departure() {
        return $this->belongsTo(Departure::class,'departure_id','id');
    }
}
