<?php

namespace App\Http\Controllers\Admin;


use App\ApiHelper\ApiResponse;
use App\DTOs\SpendingDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Spending\CreateSpendingRequest;
use App\Http\Requests\Spending\UpdateSpendingRequest;
use App\Http\Resources\SpendingResource;
use App\Repositories\interfaces\User\SpendingRepositoryInterface;
use App\Services\User\SpendingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SpendingController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected SpendingService $spendingService,
    )
    {
    }

    public function create(CreateSpendingRequest $request)
    {
        $spendingDTO=SpendingDTO::fromRequest($request);
        $spendingDTO->date=Carbon::now();
        $this->spendingService->create($spendingDTO);
        return $this->success(null,__('spending.create'));
    }
    public function update(int $id,UpdateSpendingRequest $request)
    {
        $spendingDTO=SpendingDTO::fromRequest($request);
        $this->spendingService->update($id,$spendingDTO);
        return $this->success(null,__('spending.update'));

    }
    public function getAll(int $counter_id , Request $request)
    {
        $spendings=$this->spendingService->getAll($counter_id,$request);
        return $this->successWithPagination(SpendingResource::collection($spendings));
    }

    public function delete(int $id)
    {
         $this->spendingService->delete($id);
         return $this->success(null,__('spending.delete'));
    }
    public  function getDays($counter_id)
    {
        $days=$this->spendingService->getDays($counter_id);
        return $this->success($days,__('messages.success'));
    }



}

