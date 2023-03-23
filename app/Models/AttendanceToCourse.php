<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceToCourse extends Model
{
    protected $table = 'attendance_to_course';

    public function participants()
    {
    	return $this->hasMany('App\Models\StudentsToAttendance', 'attendance_id');
    }
}
