<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'schedule_id'=>$this->schedule_id,
            'period'=>$this->period,
            'appointments' => AppointmentResouce::collection($this->whenLoaded('appointments')),
            // 'time' => $this->appointments->time,
            // 'status'=>$this->appointments->status,
            // 'attend'=>$this->appointments->attend,
            // 'status'=>$this->status,
            // 'attend'=>$this->attend,

    ];
    }
}
