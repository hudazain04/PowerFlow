<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PowerGeneratorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'generatorName'=>$this->name,
            'email'=>$this->user?->email,
            'userPhone'=>$this->user?->phone_number,
            'generatorPhones'=>$this->phones?->pluck('number'),
            'location'=>$this->location,
            'blocked'=>$this->user?->blocked,
            'kiloPrice'=>$this->settings?->kiloPrice,
            'spendingType'=>$this->settings?->spendingType,
            'day'=>$this->settings?->day,
            'afterPaymentFrequency'=>$this->settings?->afterPaymentFrequency,
            'expired_at'=>$this->subscriptions?->first()->start_time->addMonths($this->subscriptions->first()->period)->format('Y-m-d'),
        ];
    }
}
