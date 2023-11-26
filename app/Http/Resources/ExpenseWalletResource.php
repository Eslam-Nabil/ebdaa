<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseWalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'expense' => $this->expense->title ?? '',
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y'),
        ];

    }
}