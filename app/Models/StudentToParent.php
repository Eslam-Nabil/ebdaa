<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentToParent extends Model
{
    public $timestamps = false;
    protected $table = 'student_to_parents';

    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id');
    }

    public function father()
    {
        return $this->hasOne('App\Models\ParentModel', 'id', 'parent_id')
        ->where('parents.type', '=', 1);
    }

    public function mother()
    {
        return $this->hasOne('App\Models\ParentModel', 'id', 'parent_id')
        ->where('parents.type', '=', 2);
    }

    public function parent()
    {
        // return $this->belongsTo('App\Models\ParentModel', 'parent_id');
        // return $this->hasMany('App\Models\ParentModel', 'id', 'parent_id');
        return $this->hasOne('App\Models\ParentModel', 'id', 'parent_id');
    }
}
