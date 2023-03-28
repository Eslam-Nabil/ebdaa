<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['income_id','course_id','total','remaining','user'];

    public function bond()
    {
        return $this->hasMany(Bond::class, 'invoice_id');
    }
}
