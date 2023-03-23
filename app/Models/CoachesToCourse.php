<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachesToCourse extends Model
{
    public $timestamps = false;

    protected $table = 'coaches_to_course';

    public function coach()
    {
        return $this->hasOne('App\User', 'id', 'coach_id');
    }
}
