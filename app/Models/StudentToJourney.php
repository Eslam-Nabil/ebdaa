<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentToJourney extends Model
{
    use SoftDeletes;
    //
    protected $table = 'student_to_journey';    

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function journey()
    {
        return $this->belongsTo('App\Models\Journey', 'journey_id', 'id');
    }
}
