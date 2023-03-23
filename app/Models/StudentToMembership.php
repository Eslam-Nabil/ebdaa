<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentToMembership extends Model
{
    public $timestamps = false;
    protected $table = 'student_to_memberships';

    public function membership()
    {
        return $this->hasOne('App\Models\Membership', 'id', 'membership_id');
    }
}
