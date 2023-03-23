<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentsToCourse extends Model
{
    protected $table = 'students_to_course';

    public function application()
    {
        return $this->hasOne('App\Models\Application', 'id', 'application_id');
    }

    public function course()
    {
    	return $this->belongsTo('App\Models\Course', 'course_id', 'id');
    }

    public function owner()
    {
    	return $this->hasOne(
            'App\User',
            'id',
    		'user_id'
    	);
    }
}
