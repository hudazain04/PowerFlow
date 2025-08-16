<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PowerGeneratorResource extends JsonResource
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
            'email'=>$this->email,
            'phone'=>$this->phone,
            'location'=>$this->location,
            'expired_at'=>$this->expired_at->format('Y-m-d'),
        ];
    }
}
