<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable=['title','isCourse'];
    public function bond()
    {
        return $this->hasMany(Bond::class, 'income_id');
    }
}
