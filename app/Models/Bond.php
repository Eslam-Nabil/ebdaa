<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bond extends Model
{
    protected $fillable = ['invoice_id','amount','income_id','user','createdBy','acceptedBy'];

    public function incomeType()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'acceptedBy');
    }

}
