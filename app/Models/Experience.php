<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
     public function destinationExperiences()
    {
        return $this->hasMany(DestinationExperience::class, 'experience_id', 'id');
    }
}
