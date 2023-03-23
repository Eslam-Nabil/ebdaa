<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    /*public function s2p()
    {
        return $this->belongsTo('App\Models\StudentToParent', 'student_id');
    }*/

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function parents()
    {
        return $this->hasMany('App\Models\ParentModel', 'id');
    }

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'owner_id');
    }

    public function s2c()
    {
        return $this->hasMany('App\Models\StudentsToCourse', 'application_id');
    }

    public function courses()
    {
        return $this->hasManyThrough('App\Models\Course', 'App\Models\StudentsToCourse', 'application_id', 'id', 'id', 'course_id');
    }
}
