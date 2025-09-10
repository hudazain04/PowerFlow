<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
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
          'name'=>$this->name,
            'neighborhoodName'=>$this->neighborhood->name,
            'neighborhood_id'=>$this->neighborhood_id,
            'boxes'=>ElectricalBoxResource::collection($this->electricalbox),

        ];
    }
}
