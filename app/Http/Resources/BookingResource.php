<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            // 'profile_id'=>$this->profile_id,
            'profile'=>[
                'id'=>$this->profile->id,
                'name'=>$this->profile->name,
                'email'=>$this->profile->email,
                'mobile'=>$this->profile->mobile,
                'password'=>$this->profile->password,
                'spelization_id'=>$this->profile->spelization_id,
                'title'=>$this->profile->title,
                'description'=>$this->profile->description,
                'countryNameCode'=>$this->profile->countryNameCode,
                'countryPhoneCode'=>$this->profile->countryPhoneCode,
                'registerationDate'=>$this->profile->created_at,
                'image'=>$this->profile->image,
                'active'=>$this->profile->active,
                'hide'=>$this->profile->hide,
                'availableForWork'=>$this->profile->availableForWork,
                'trusted'=>$this->profile->trusted,
                'token'=>$this->profile->token,
                'fcm_token'=>$this->profile->fcm_token,
                'lock'=>$this->profile->lock,
                'font_color'=>$this->profile->font_color,
                'background_color'=>$this->profile->background_color,
            ],
            // 'user_id'=>$this->user_id,
            'user'=>[
                'id'=>$this->user->id,
                'name'=>$this->user->name,
                'email'=>$this->user->email,
                'mobile'=>$this->user->mobile,
                'password'=>$this->user->password,
                'spelization_id'=>$this->user->spelization_id,
                'title'=>$this->user->title,
                'description'=>$this->user->description,
                'countryNameCode'=>$this->user->countryNameCode,
                'countryPhoneCode'=>$this->user->countryPhoneCode,
                'registerationDate'=>$this->user->created_at,
                'image'=>$this->user->image,
                'active'=>$this->user->active,
                'hide'=>$this->user->hide,
                'availableForWork'=>$this->user->availableForWork,
                'trusted'=>$this->user->trusted,
                'token'=>$this->user->token,
                'fcm_token'=>$this->user->fcm_token,
                'lock'=>$this->user->lock,
                'font_color'=>$this->user->font_color,
                'background_color'=>$this->user->background_color,
            ],
            // 'address_id'=>$this->address_id,
            'address'=>[
                'id'=>$this->address->id,
                'user_id'=>$this->address->user_id,
                'address'=>$this->address->address,
                'note'=>$this->address->note,
                'lat'=>$this->address->lat,
                'lang'=>$this->address->lang,
            ],
            // 'schedule_id'=>$this->schedule_id,
            'schedule'=>[
                'id'=>$this->id,
                'user_id'=>$this->user_id,
                'address_id'=>$this->address_id,
                'day'=>$this->day,
                'date'=>$this->date,
                'address'=>$this->address->address,
                'note'=>$this->address->note,
                'lat'=>$this->address->lat,
                'lang'=>$this->address->lang,
            ],
            // 'period_id'=>$this->period_id,
            'period'=>[
                'id'=>$this->period->id,
                'schedule_id'=>$this->period->schedule_id,
                'period'=>$this->period->period,
            ],
            // 'appointment_id'=>$this->appointment_id,
            'appointment'=>[
                'id'=>$this->appointment->id,
                'schedule_id'=>$this->appointment->schedule_id,
                'period_id'=>$this->appointment->period_id,
                'time'=>$this->appointment->time,
                'status'=>$this->appointment->status,
                'attend'=>$this->appointment->attend,
            ],
            'confirm_status'=>$this->confirm_status,
        ];
    }
}
