<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    public function experinceOne() {
        return $this->belongsTo(Experience::class,'experience1','id');
    }

    public function experinceTwo() {
        return $this->belongsTo(Experience::class,'experience2','id');
    }
    public function experinceThree() {
        return $this->belongsTo(Experience::class,'experience3','id');
    }
    public function experinceFour() {
        return $this->belongsTo(Experience::class,'experience4','id');
    }
     public function experinceFive() {
        return $this->belongsTo(Experience::class,'experience5','id');
    }
}
