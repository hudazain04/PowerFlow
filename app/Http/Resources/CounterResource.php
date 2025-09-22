<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterResource extends JsonResource
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
            'number'=>$this->number,
            'QRCode'=>$this->QRCode,
            'status'=>$this->status,
            'current_spending'=>$this->current_spending,
            'spendingType'=>$this->spendingType,
            'physical_device_id'=>$this->physical_device_id,
            'box_id'=> $this->electricalBoxes->first() ? $this->electricalBoxes->first()->id : null,
            'user'=>[
                'email'=>$this->user->email,
                'phone_number'=>$this->user->phone_number
            ]
        ];
    }
}
