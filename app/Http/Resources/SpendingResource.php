<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpendingResource extends JsonResource
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
          'counter_id'=>$this->counter_id,
            'consume' => $this->consume,
          'date'=>$this->date->format('Y-m-d H:s a')
        ];
    }
}
