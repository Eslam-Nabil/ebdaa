<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimesToCourse extends Model
{
	use SoftDeletes;
	
    public $timestamps = false;

    protected $table = 'times_to_course';

    public function course()
    {
    	return $this->belongsTo('App\Models\Course', 'course_id');
    }
}
