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
        if ($feature)
        {
            return FeatureDTO::fromModel($feature);
        }
        else
        {
            throw new ErrorException(__('feature.notFound'),ApiCode::NOT_FOUND);
        }

    }

    public function create(FeatureDTO $featureDTO): FeatureDTO
    {
        $feature=FeatureModel::create($featureDTO->toArray());
        return FeatureDTO::fromModel($feature);
    }

    public function update(int $id, FeatureDTO $featureDTO): FeatureDTO
    {
        $feature=FeatureModel::find($id);
        if ($feature)
        {
            $feature->update($featureDTO->toArray());
            $feature->save();
            return $featureDTO::fromModel($feature);
        }
        else
        {
            throw new ErrorException(__('feature.notFound'),ApiCode::NOT_FOUND);
        }


    }

    public function delete(int $id): bool
    {
        $feature=FeatureModel::find($id);
        if ($feature)
        {
            return $feature->delete();
        }
        else
        {
            throw new ErrorException(__('feature.notFound'),ApiCode::NOT_FOUND);
        }
    }
}
