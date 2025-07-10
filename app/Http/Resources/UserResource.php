<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name'=>$this->fullName(),
            'email' => $this->email,
            'password'=>$this->password,
            'phone_number'=>$this->phone_number,
            'role'=>$this->getRoleNames(),


        ];
//        'roles' => $this->roles->map(function ($role) {
//        return [
//            'id' => $role->id,
//            'name' => $role->name,
//            // Include other role attributes if needed
//        ];
    }
}
