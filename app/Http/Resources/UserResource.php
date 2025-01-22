<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'mobile'=>$this->mobile,
            'password'=>$this->password,
            'spelization_id'=>$this->spelization_id,
            'title'=>$this->title,
            'description'=>$this->description,
            'countryNameCode'=>$this->countryNameCode,
            'countryPhoneCode'=>$this->countryPhoneCode,
            'registerationDate'=>$this->registerationDate,
            // 'registerationDate'=>$this->created_at,
            'image'=>$this->image,
            'active'=>$this->active,
            'hide'=>$this->hide,
            'availableForWork'=>$this->availableForWork,
            'trusted'=>$this->trusted,
            'token'=>$this->token,
            'fcm_token'=>$this->fcm_token,
            'lock'=>$this->lock,
            'font_color'=>$this->font_color,
            'background_color'=>$this->background_color,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'schedules_count' => $this->schedules_count, // Include total schedules count
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'social_accounts'=>SocialaccountResouce::collection($this->whenLoaded('socialaccounts')),
            'offers' => OfferResource::collection($this->whenLoaded('offers')),
            'experiences' => ExperienceResource::collection($this->whenLoaded('experiences')),
            'certificates' => CertificateResource::collection($this->whenLoaded('certificates')),
            'rates' => RateResource::collection($this->whenLoaded('rates')),
        ];
    }
}
