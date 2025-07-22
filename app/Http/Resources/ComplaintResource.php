<?php

namespace App\Http\Resources;

use App\Types\ComplaintTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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
            'description'=>$this->description,
            'type'=>$this->type,
            'user_id'=>$this->user_id,
        ];
       if ($this->type==ComplaintTypes::Cut)
        {
            $data=array_merge($data,[
                'status'=>$this->status,
                'counter_id'=>$this->counter_id,
                'employee_id'=>$this->employee_id,
            ]);
        }
        return $data;
    }
}
