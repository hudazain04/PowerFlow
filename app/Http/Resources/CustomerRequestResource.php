<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRequestResource extends JsonResource
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
            'spendingType'=>$this->spendingType,
            'status'=>$this->status,
            'user_notes'=>$this->user_notes,
            'admin_notes'=>$this->admin_notes,
            'user'=>UserResource::make($this->user),
            'box'=>ElectricalBoxResource::make($this->box),

        ];
    }
}
