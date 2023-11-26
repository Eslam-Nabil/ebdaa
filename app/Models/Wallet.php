<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'type', 'amount','expenses_id'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function expense()
    {
        return $this->belongsTo('App\Models\Expenses', 'expenses_id');
    }
}