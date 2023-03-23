<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentsToAttendance extends Model
{
    protected $table = 'students_to_attendance';

    public function application()
    {
    	return $this->hasOne('App\Models\Application', 'id', 'application_id');
    }

    public function attendance()
    {
    	return $this->belongsTo('App\Models\AttendanceToCourse');
    }
}
