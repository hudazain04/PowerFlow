<?php

namespace App\Services\SuperAdmin;
use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Http\Requests\Feature\CreateFeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Repositories\interfaces\SuperAdmin\FeatureRepositoryInterface;


use App\DTOs\FeatureDTO;

class FeatureService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(protected FeatureRepositoryInterface $featureRepository)
    {
        //
    }

    public function getAll()
    {
        $features= $this->featureRepository->all();
        return $this->success(FeatureResource::collection($features),__('messages.success'));

    }

    public function create(CreateFeatureRequest $request)
    {
        $featureDTO=FeatureDTO::fromRequest($request);
        $feature= $this->featureRepository->create($featureDTO);
        return $this->success(FeatureResource::make($feature),__('feature.create'),ApiCode::CREATED);
    }

    public function update(int $id , CreateFeatureRequest $request)
    {
        $featureDTO=FeatureDTO::fromRequest($request);
        $feature=$this->featureRepository->update($id,$featureDTO);
        return $this->success(FeatureResource::make($feature),__('feature.update'));
    }

    public function find(int $id)
    {
        $feature=$this->featureRepository->find($id);
        return $this->success(FeatureResource::make($feature),__('messages.success'));

    }

    public function delete(int $id)
    {
        $this->featureRepository->delete($id);
        return $this->success(__('feature.delete'));
    }



}
