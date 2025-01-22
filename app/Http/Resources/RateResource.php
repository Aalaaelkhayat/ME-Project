<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'profile_id'=>$this->profile_id,
            'user_image'=>$this->user->image,
            'user_name'=>$this->user->name,
            'rating'=>$this->rating,
            'comment'=>$this->comment,
            'date'=>$this->date,
    ];
    }
}
