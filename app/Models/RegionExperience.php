<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionExperience extends Model
{
    public function experience()
    {
        return $this->belongsTo(Experience::class, 'experience_id');
    }
}
