<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable=['title','isCourse'];
    
    public function invoice()
    {
        return $this->hasMany(Bond::class, 'income_id');
    }
}
