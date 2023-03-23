<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CourseTitle extends Model
{
    public $timestamps = false;

    protected $table = 'courses_titles';

    public function coaches()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id');
    }
}
