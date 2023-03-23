<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    
    public function title()
    {
        return $this->hasOne('App\Models\CourseTitle', 'id', 'title_id');
    }

    public function coaches()
    {
        return $this->hasMany('App\Models\CoachesToCourse', 'course_id', 'id');
    }

    public function times()
    {
        return $this->hasMany('App\Models\TimesToCourse', 'course_id', 'id');
    }

    public function participants()
    {
        return $this->hasMany(
            'App\Models\StudentsToCourse',
            'course_id',
            'id'
        );
    }

    public function attendanceToCourse()
    {
        return $this->hasMany(
            'App\Models\AttendanceToCourse',
            'course_id',
            'id'
        );
    }
}
