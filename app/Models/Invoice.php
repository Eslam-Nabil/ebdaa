<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['income_id','course_id','total','remaining','student_id'];

    public function bond()
    {
        return $this->hasMany(Bond::class, 'invoice_id');
    }
    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }
}
