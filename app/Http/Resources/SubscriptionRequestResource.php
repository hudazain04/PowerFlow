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
            'generatorName'=>$this->name,
            'email'=>$this->user ? $this->user->email : null,
            'userPhone'=>$this->user ? $this->user->phone_number : null,
            'generatorPhones'=>$this->phones,
            'plan'=>$this->planPrice?->plan ? $this->planPrice->plan->name : null,
            'price'=>$this->planPrice ? $this->planPrice->price : null,
            'period'=>$this->period,
            'subscriptionType'=>$this->type,
            'status'=>$this->status,
            'kiloPrice'=>$this->kiloPrice,
            'spendingType'=>$this->spendingType,
            'day'=>$this->day,
            'afterPaymentFrequency'=>$this->afterPaymentFrequency,
            'time'=>$this->created_at->format('Y-m-d  h:iA'),
        ];
    }
}
