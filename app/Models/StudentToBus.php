<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentToBus extends Model
{
    //
    protected $table = 'student_to_bus';
    public $timestamps = false;

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function bus()
    {
        return $this->belongsTo('App\Models\Bus', 'bus_id', 'id');
    }
}
