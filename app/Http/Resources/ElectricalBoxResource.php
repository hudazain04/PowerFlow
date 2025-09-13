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
//            'area' => $this->whenLoaded('areas', function () {
                'area' => $this->whenLoaded('areas', function () {
                    // Get the first area and format it as an object
                    if ($area = $this->areas->first()) {
                        return [
                            'id' => $area->id,
                            'name' => $area->name,
                        ];
                    }


            }),
        ];
    }
}
