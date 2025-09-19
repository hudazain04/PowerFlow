<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribedGeneratorResource extends JsonResource
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
            'phone'=>$this->user->phone_number,
            'expired_at'=>$this->subscriptions->first()->start_time->addMonths($this->subscriptions->first()->period)->format('Y-m-d'),
        ];
    }
}
