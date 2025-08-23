<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;


class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        if ( str_contains(Route::current()->uri(), 'api/feature'))
        {
            $data= [
                'id'=>$this->id,
                'key'=>$this->key,
                'value'=> $this->value,
                'hasValue'=>$this->hasValue,
                'description' =>$this->description,
            ];
        }
        else
            {
                $data=[
                    'id'=>$this->id,
                    'key'=>$this->key,
                    'value'=> $this->value,
//                 'description' => $this->value !== null
//                ? str_replace('{}', ' ' . $this->value . ' ', $this->description)
//                : str_replace('{}', '' , $this->description),
                    'description' =>$this->value !== null
                            ? str_replace('{}', ' ' . $this->value . ' ', $this->description)
                            : str_replace('{}', '', $this->description),
                ];
            }
        return $data;

    }
}
