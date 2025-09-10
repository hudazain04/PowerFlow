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
            'id'=>$this->id,
          'number'=>$this->number,
          'location'=>$this->location,
          'capacity'=>$this->capacity,
            'counters_count' => $this->counters_count,
            'maps'=>[
                'x'=>$this->latitude,
                'y'=>$this->longitude,
            ],
            'areas' => $this->whenLoaded('areas', function () {
                return $this->areas->map(function ($area) {
                    return [
                        'id' => $area->id,
                        'name' => $area->name,
                    ];
                });
            }),
        ];
    }
}
