<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Musonza\Chat\Traits\Messageable;

class ParentModel extends Authenticatable
{
    use Messageable, Notifiable;
    protected $table = 'parents';

    protected $guard = 'parent';

    protected $fillable = [
        'id', 'code'
    ];

    protected $hidden = [
        'remember_token', 'api_token', 'code',
    ];

    public function generateToken()
    {
        $token = str_random(8);
        $this->api_token = hash('sha256', $token);
        $this->code = $token;
        $this->save();
        return $token;
    }

    public function children()
    {
        return $this->hasManyThrough(
            'App\Models\Student',
            'App\Models\StudentToParent',
            'parent_id',
            'id',
            'id',
            'student_id'
        );
    }

    // public function parents()
    // {
    //     return $this->belongsTo('App\Models\StudentToParent', 'parent_id');
    // }

    // public function parents()
    // {
    //     return $this->hasMany(
    //         'App\Models\StudentToParent', 'id'
    //     );
    //     /*return $this->hasManyThrough(
    //         'App\Models\StudentToParents',
    //         'App\Models\Parent'
    //     );*/
    // }
}
