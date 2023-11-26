<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseRequestResource extends JsonResource
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
            'expense' => $this->expense->title,
            'amount' => $this->amount,
            'createdBy' => $this->created_by->name,
            'acceptedBy' => $this->accepted_by->name ?? 'Not accepted yet',
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y'),
        ];

    }
}