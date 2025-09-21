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

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $neighborhood = Neighborhood::findOrFail($id);
            $neighborhood->update($data);
            return $neighborhood->fresh(); // Returns refreshed model instance
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $neighborhood = Neighborhood::findOrFail($id);
            $neighborhood->delete();
            return true;
        });
    }


}
