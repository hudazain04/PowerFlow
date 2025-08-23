<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElectricalBoxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'number'=>$this->number,
          'location'=>$this->location,
          'capacity'=>$this->capacity,
            'maps'=>[
                'x'=>$this->latitude,
                'y'=>$this->longitude,
            ],
        ];
    }
}
