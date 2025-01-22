<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'address'=>$this->address,
            'note'=>$this->note,
            'lat'=>$this->lat,
            'lang'=>$this->lang,
            'schedules_count' => $this->schedules_count, // Count of schedules for this address
            'mobiles' => MobileResource::collection($this->whenLoaded('mobiles')),

    ];
    }
}
