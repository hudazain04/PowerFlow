<?php

namespace App\Repositories\Eloquent\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\DTOs\FeatureDTO;
use App\Exceptions\ErrorException;
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

    public function all(): Collection
    {
        $features=FeatureModel::all();
        return $features->map(function ($feature){
            return FeatureDTO::fromModel($feature);
        });

    }

    public function find(int $id): FeatureDTO
    {
        $feature=FeatureModel::find($id);
        return FeatureDTO::fromModel($feature);

    }

    public function create(FeatureDTO $featureDTO): FeatureDTO
    {
        $feature=FeatureModel::create($featureDTO->toArray());
        return FeatureDTO::fromModel($feature);
    }

    public function update(FeatureDTO $feature, FeatureDTO $featureDTO): FeatureDTO
    {
            $feature=$feature->toModel(FeatureModel::class);
            $feature->update($featureDTO->toArray());
            $feature->save();
            return $featureDTO::fromModel($feature);
    }

    public function delete(FeatureDTO $featureDTO): bool
    {
        $feature=$featureDTO->toModel(FeatureModel::class);
        return $feature->delete();

    }
}
