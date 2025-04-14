<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\Application\Feature\UseCases\CreateHandler;
use App\Application\Feature\UseCases\DeleteHandler;
use App\Application\Feature\UseCases\FindHandler;
use App\Application\Feature\UseCases\GetAllHandler;
use App\Application\Feature\UseCases\UpdateHandler;
use App\Domain\Feature\DTOs\FeatureDTO;
use App\Http\Requests\Feature\CreateFeatureRequest;
use App\Http\Resources\FeatureResource;
use Illuminate\Http\Request;
use PHPUnit\Event\Code\Throwable;

class FeatureController extends Controller
{
    use ApiResponse;

    public function index(GetAllHandler $handler)
    {
        try {
            $feature=$handler->handle();
            return $this->success(FeatureResource::collection($feature),__('messages.success'));
        }
        catch (\Throwable $throwable)
        {
            return $this->serverError();
        }
    }

    public function store(CreateHandler $handler,CreateFeatureRequest $request)
    {
        try {
            $featureDTO=FeatureDTO::fromRequest($request);
            $feature=$handler->handle($featureDTO);
            return $this->success(FeatureResource::make($feature),__('feature.created'),ApiCode::CREATED);
        }
        catch (\Throwable $throwable)
        {
            return $this->serverError();
        }

    }

    public function update(UpdateHandler $handler,int $id , CreateFeatureRequest $request)
    {
        try {
            $featureDTO=FeatureDTO::fromRequest($request);
            $feature=$handler->handle($id,$featureDTO);
            return $this->success(FeatureResource::make($feature),__('feature.update'));
        }
        catch (\Throwable $throwable)
        {
            return $this->serverError();
        }
    }

    public function delete(int $id , DeleteHandler $handler)
    {
        try {
            $handler->handle($id);
            return $this->success(__('feature.delete'));
        }
        catch (\Throwable $throwable)
        {
            return $this->serverError();
        }
    }

    public function findById(FindHandler $handler, int $id)
    {
        try {
            $feature=$handler->handle($id);
            return $this->success(FeatureResource::make($feature),__('messages.success'));
        }
        catch (\Throwable $throwable)
        {
            return $this->serverError();
        }
    }
}
