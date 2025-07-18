<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribedGeneratorResource extends JsonResource
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
            'generatorName'=>$this->name,
            'phone'=>$this->phone,
            'expired_at'=>$this->expired_at->format('Y-m-d'),
        ];
    }
}
