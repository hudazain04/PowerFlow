<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Feature\DTOs\FeatureDTO;
use App\Domain\Feature\Entities\Feature;
use App\Domain\Feature\Repositories\FeatureRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Feature as FeatureModel;


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
        $feature=FeatureModel::findOrFail($id);
        return FeatureDTO::fromModel($feature);
    }

    public function create(FeatureDTO $featureDTO): FeatureDTO
    {
        $feature=FeatureModel::create($featureDTO->toArray());
        return FeatureDTO::fromModel($feature);
    }

    public function update(int $id, FeatureDTO $featureDTO): FeatureDTO
    {
        $feature=FeatureModel::findOrFail($id);
        $feature->update($featureDTO->toArray());
        $feature->save();
        return $featureDTO::fromModel($feature);

    }

    public function delete(int $id): bool
    {
        $feature=FeatureModel::findOrFail($id);
        return $feature->delete();
    }
}
