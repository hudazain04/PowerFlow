<?php

namespace App\Http\Resources;

use App\Types\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data=[
            'id'=>$this['user']->id,
            'full_name'=>$this['user']->fullName(),
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this['user']->email,
            'phone_number'=>$this['user']->phone_number,
            'blocked'=>$this['user']->blocked,
            'role'=>$this['user']->getRoleNames()->join(','),
            'shouldPay'=>$this['shouldPay'],
            'lastSubscriptionRequest'=>SubscriptionRequestResource::make($this['lastSubscriptionRequest']),
        ];
        return $data;
    }
}
