<?php

namespace App;

use App\Models\Course;
use App\Models\CourseTitle;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Musonza\Chat\Traits\Messageable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, Messageable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    public function generateToken()
    {
        $token = str_random(8);
        $this->api_token = hash('sha256', $token);
        $this->code = $token;
        $this->save();
        return $token;
    }

    public function userGroup()
    {
        return $this->hasOne('App\Models\UserGroup', 'id', 'group_id');
    }
    public function createdBy()
    {
        return $this->hasMany('App\Models\Bond', 'createdBy');
    }
    public function acceptedBy()
    {
        return $this->hasMany('App\Models\Bond', 'acceptedBy');
    }
    /**
     * The courses that belong to the user.
     */
    public function courses()
    {
        return $this->belongsToMany(CourseTitle::class, 'course_user', 'user_id', 'course_id');
    }
}
