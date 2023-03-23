<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journey extends Model
{
    use SoftDeletes;

    protected $dates = ["deleted_at"];

    //
    public function bus()
    {
        return $this->hasOne('App\Models\Bus', 'id', 'bus_id');
    }

    public function driver()
    {
        return $this->hasOne('App\Models\Driver', 'id', 'driver_id');
    }

    public function students()
    {
        return $this->hasMany('App\Models\StudentToJourney', 'journey_id', 'id');
    }
}
