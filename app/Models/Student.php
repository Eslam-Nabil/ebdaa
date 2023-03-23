<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    public function s2p()
    {
        return $this->hasMany('App\Models\StudentToParent', 'student_id');
    }

    public function s2j()
    {
        return $this->hasMany('App\Models\StudentToJourney', 'student_id');
    }

    public function school()
    {
        return $this->hasOne('App\Models\School', 'id', 'school_id');
    }

    public function relatives()
    {
        return $this->hasMany('App\Models\Relative', 'student_id');
    }

    public function parents()
    {
        return $this->hasManyThrough(
            'App\Models\ParentModel',
            'App\Models\StudentToParent',
            'student_id',
            'id',
            'id',
            'parent_id'
        );
    }

    public function journeys()
    {
        return $this->hasManyThrough(
            'App\Models\Journey',
            'App\Models\StudentToJourney',
            'student_id',
            'id',
            'id',
            'journey_id'
        );
    }

    public function father()
    {
        return $this->hasManyThrough(
            'App\Models\ParentModel',
            'App\Models\StudentToParent',
            'student_id',
            'id',
            'id',
            'parent_id'
        )->where('parents.type', '=', 1);
    }

    public function dad()
    {
        return $this->hasManyThrough(
            'App\Models\ParentModel',
            'App\Models\StudentToParent',
            'parent_id',
            'id',
            'id',
            'student_id'
        )->where('parents.type', '=', 1);
    }

    public function mother()
    {
        return $this->hasManyThrough(
            'App\Models\ParentModel',
            'App\Models\StudentToParent',
            'student_id',
            'id',
            'id',
            'parent_id'
        )->where('parents.type', '=', 2);
    }

    public function memberships()
    {
        return $this->hasMany('App\Models\StudentToMembership', 'student_id');
        return $this->hasManyThrough(
            'App\Models\Membership',
            'App\Models\StudentToMembership',
            'membership_id',
            'id',
            'id',
            'id'
        );
    }

    public function buses()
    {
        return $this->hasManyThrough(
            'App\Models\Bus', 
            'App\Models\StudentToBus', 
            'student_id', 
            'id', 
            'id', 
            'bus_id'
        );
    }

    public function application()
    {
        return $this->hasOne('App\Models\Application', 'student_id', 'id');
    }
}
