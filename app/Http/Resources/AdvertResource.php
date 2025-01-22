<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertResource extends JsonResource
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
            'title'=>$this->title,
            'body'=>$this->body,
            'date'=>$this->date,
            'date'=>$this->date,
            'user_id'=>$this->user->id,
            'user_name'=>$this->user->name,
            'user_image'=>$this->user->image,
            'user_trusted'=>$this->user->trusted,
            'user_spelization_id'=>$this->user->spelization_id,
            'user_availableForWork'=>$this->user->availableForWork,
            'user_title'=>$this->user->title,
            'user_description'=>$this->user->description,
    ];
    }
}
