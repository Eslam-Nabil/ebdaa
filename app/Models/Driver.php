<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Authenticatable
{
    use SoftDeletes;

    protected $dates = ["deleted_at"];
    
    //
    protected $fillable = [
        'name', 'code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token',
    ];

    public function generateToken()
    {
        $token = str_random(8);
        $this->api_token = hash('sha256', $token);
        $this->code = $token;
        $this->save();
        return $token;
    }

    public function buses()
    {
        return $this->hasOne('App\Models\Bus','driver_id', 'id');
    }
}
