<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class clientsResource extends JsonResource
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
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'counters_count' => $this->counters_count,
//            'data' => UserWithCountersResource::collection($this->collection),
//            'links' => [
//                'first' => $this->url(1),
//                'last' => $this->url($this->lastPage()),
//                'prev' => $this->previousPageUrl(),
//                'next' => $this->nextPageUrl(),
//            ],
//            'meta' => [
//                'current_page' => $this->currentPage(),
//                'from' => $this->firstItem(),
//                'last_page' => $this->lastPage(),
//                'path' => $this->path(),
//                'per_page' => $this->perPage(),
//                'to' => $this->lastItem(),
//                'total' => $this->total(),
//            ]
//        ];
];

    }
}
