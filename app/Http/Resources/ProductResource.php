<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
                'id'=>$this->id,
                'user_id'=>$this->user_id,
                'title'=>$this->title,
                'description'=>$this->description,
                'date'=>$this->date,
                'active'=>$this->active,
                'images' => ImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
