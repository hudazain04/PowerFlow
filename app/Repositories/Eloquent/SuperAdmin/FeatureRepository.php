<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\Models\Feature as FeatureModel;
use App\Repositories\interfaces\SuperAdmin\FeatureRepositoryInterface;
use Illuminate\Support\Collection;

class FeatureRepository implements FeatureRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function all(?array $filters=[]): Collection
    {
        $features=FeatureModel::filter($filters)->get();
        return $features;

    }

    public function find(int $id): FeatureModel
    {
        $feature=FeatureModel::find($id);
        return $feature;

    }

    public function create(array $data): FeatureModel
    {
        $feature=FeatureModel::create($data);
        return $feature;
    }

    public function update(FeatureModel $feature, array $data): FeatureModel
    {
            $feature->update($data);
            $feature->save();
            return $feature;
    }

    public function delete(FeatureModel $feature): bool
    {
        return $feature->delete();

    }
}
