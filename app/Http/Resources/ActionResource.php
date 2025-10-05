<?php

namespace App\Http\Resources;

use App\Types\ActionTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data=[
            'id'=>$this->id,
            'type'=>$this->type,
            'counter'=>CounterResource::make($this->counter),
            'status'=>$this->status,
            'employee_id'=>$this->employee_id,
            'priority'=>$this->priority,
            'parent'=>$this->parent,
        ];
        if ($this->type===ActionTypes::Payment & $this->relatedData)
        {
            array_merge($data,['payment'=>$this->relatedData['payment']]);
        }
        if ($this->type ===ActionTypes::Cut & $this->relatedData)
        {
            array_merge($data,['latestSpending'=>$this->relatedData['latestSpending'],'latestPayment'=>$this->relatedData['latestPayment']]);
        }
        return $data;
    }
}
