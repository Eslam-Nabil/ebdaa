<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    //
    use SoftDeletes;

    protected $dates = ["deleted_at"];

    public function driver()
    {
        return $this::hasOne('App\Models\Driver', 'id', 'driver_id');
    }

    public function students()
    {
        return $this::hasMany('App\Models\StudentToBus', 'bus_id', 'id');
    }

    public function journeys()
    {
        return $this::hasMany('App\Models\Journey', 'bus_id', 'id');
    }
}
