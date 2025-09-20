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
            'usePhone'=>$this->user?->phone_number,
            'generatorPhones'=>$this->phones->pluck('number'),
            'location'=>$this->location,
            'kiloPrice'=>$this->settings->kiloPrice,
            'spendingType'=>$this->settings->spendingType,
            'day'=>$this->settings->day,
            'afterPaymentFrequency'=>$this->settings->afterPaymentFrequency,
            'expired_at'=>$this->user->expired_at?->format('Y-m-d'),
        ];
    }
}
