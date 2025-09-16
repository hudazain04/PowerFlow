<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpendingPaymentRersource extends JsonResource
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
            'date'=>$this->date->format('Y-m-d H:s a'),
            'amount'=>$this->amount,
            'current_spending'=>$this->current_spending,
            'next_spending'=>$this->next_spending,
            'counter_id'=>$this->counter_id,
            'status'=>$this->status,
            'type'=>$this->type,
        ];
    }
}
