<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'generatorName'=>$this->powerGenerator ? $this->powerGenerator->name : null,
            'email'=>$this->user ? $this->user->email : null,
            'phone'=>$this->user ? $this->user->phone_number : null,
            'plan'=>$this->plan ? $this->plan->name : null,
            'price'=>$this->planPrice ? $this->planPrice->price : null,
            'period'=>$this->period,
            'type'=>$this->type,
            'time'=>$this->created_at->format('Y-m-d  h:iA'),


        ];
    }
}
