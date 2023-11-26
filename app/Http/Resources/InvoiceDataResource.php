<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceDataResource extends JsonResource
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
            'title' => $this->income->title == 'Courses' ? $this->income->title . ": " . $this->course->title->title : $this->income->title,
            'student' => $this->student->name,
            'total' => $this->total,
            'remaining' => $this->remaining,
            'application' => $this->student->application->id ?? null,
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y'),

        ];
    }
}