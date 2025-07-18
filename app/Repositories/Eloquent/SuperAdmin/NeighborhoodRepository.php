<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\Models\Neighborhood;
use App\Repositories\interfaces\SuperAdmin\NeighborhoodRepositoryInterface;
use Illuminate\Support\Facades\DB;

class NeighborhoodRepository implements NeighborhoodRepositoryInterface
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return  Neighborhood::create($data);
        });
    }

    public function findById(int $id)
    {
        return Neighborhood::findOrFail($id);
    }

    public function listAll()
    {
        return Neighborhood::withCount('areas')->get();
    }


}
