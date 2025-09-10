<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
          'user_name'=>$this->user_name,
            'phone_number'=>$this->phone_number,
            'secret_key'=>$this->secret_key,
            'permissions' => $this->roles->flatMap(fn($role) => $role->getPermissionNames())
                ->merge($this->getPermissionNames())
                ->unique()
                ->values(),
        ];
    }
}
