<?php

namespace App\Http\Resources;

use App\Types\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'full_name'=>$this->fullName(),
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'blocked'=>$this->blocked,
            'role'=>$this->getRoleNames()->join(','),
        ];

        if ($this->hasRole(UserTypes::ADMIN))
        {
            $generator=$this->powerGenerator;
            $data=array_merge($data,[
                'generator_id'=>$generator->id,
                'generatorName'=>$generator->name,
                'phones'=>$generator->phones?->pluck('number'),
                'location'=>$generator->location,
                'blocked'=>$generator->user?->blocked,
                'kiloPrice'=>$generator->settings->kiloPrice,
                'spendingType'=>$generator->settings->spendingType,
                'day'=>$generator->settings->day,
                'afterPaymentFrequency'=>$generator->settings->afterPaymentFrequency,
            ]);
        }
        return $data;
    }
}
