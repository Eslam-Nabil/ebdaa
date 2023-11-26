<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseRequest extends Model
{
    protected $fillable = ['expenses_id', 'amount', 'createdBy', 'acceptedBy'];

    public function expense()
    {
        return $this->belongsTo(Expenses::class, 'expenses_id');
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