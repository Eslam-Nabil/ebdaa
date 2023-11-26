<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bond extends Model
{
    protected $fillable = ['invoice_id', 'amount', 'createdBy', 'acceptedBy'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function created_by()
    {
        return $this->belongsTo('App\User', 'createdBy');
    }
    public function accepted_by()
    {
        return $this->belongsTo('App\User', 'acceptedBy');
    }

}