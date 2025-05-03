<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\FeatureDTO;
use App\Exceptions\ErrorException;
use App\Http\Requests\Feature\CreateFeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Services\SuperAdmin\FeatureService;
use Illuminate\Http\Request;
use PHPUnit\Event\Code\Throwable;

class FeatureController extends Controller
{
    use ApiResponse;

    public function __construct(protected FeatureService $featureService)
    {

    }

    public function index()
    {
             return $this->featureService->getAll();
    }

    public function store(CreateFeatureRequest $request)
    {
            return $this->featureService->create($request);
    }

    public function update(int $id , CreateFeatureRequest $request)
    {
            return $this->featureService->update($id,$request);
    }

    public function findById( int $id)
    {
        return $this->featureService->find($id);
    }

    public function delete(int $id )
    {
           return $this->featureService->delete($id);
    }


}
