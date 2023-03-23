<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    public function school()
    {
        return $this->hasOne('App\Models\School', 'id', 'school_id');
    }

    public function referrer()
    {
        return $this->hasOne('App\Models\Application', 'referrer', 'student_id');
    }

    public function application()
    {
        return $this->hasOne('App\Models\Application', 'id', 'application_id');
    }
}
