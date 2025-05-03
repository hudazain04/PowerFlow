<?php

namespace App\Http\Resources;

use App\DTOs\FeatureDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'target'=>$this->target,
            'description'=>$this->description,
            'monthlyPrice'=>$this->monthlyPrice,
            'planPrices'=>PlanPriceResource::collection($this->planPrices),
            'feature'=>FeatureResource::collection($this->features),

        ];
    }
}
