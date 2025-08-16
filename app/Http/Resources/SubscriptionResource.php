<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'plan_id'=>$this->planPrice->plan->id,
            'start_time'=>$this->start_time->format('Y-m-d'),
            'expires_at'=>($this->start_time)->addMonths($this->period)->format('Y-m-d'),
        ];
    }
}
