<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResouce extends JsonResource
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
            'period_id'=>$this->period_id,
            'time'=>$this->time,
            'status'=>$this->status,
            'attend'=>$this->attend,
            // 'period' => new PeriodResource($this->period),

        ];
    }
}
