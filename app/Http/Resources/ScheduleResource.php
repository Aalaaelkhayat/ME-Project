<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'address_id'=>$this->address_id,
            'day'=>$this->day,
            'date'=>$this->date,
            // 'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            // 'periods' => PeriodResource::collection($this->whenLoaded('periods')),
            // 'appointments' => AppointmentResouce::collection($this->whenLoaded('appointments')),
            // 'appointments' => AppointmentResouce::collection($this->appointments),
            // 'address' => new AddressResource($this->address),
            'address'=>$this->address->address,
            'note'=>$this->address->note,
            'lat'=>$this->address->lat,
            'lang'=>$this->address->lang,
    ];
    }
}
