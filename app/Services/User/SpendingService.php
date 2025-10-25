<?php

namespace App\Services\User;

use App\ApiHelper\ApiCode;
use App\DTOs\SpendingDTO;
use App\Exceptions\ErrorException;
use App\Repositories\interfaces\Admin\SpendingRepositoryInterface;
use Illuminate\Http\Request;

class SpendingService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected SpendingRepositoryInterface $spendingRepository,
    )
    {
        //
    }

    public function create(SpendingDTO $spendingDTO)
    {
        $lastSpending=$this->spendingRepository->getLastForCounter($spendingDTO->counter_id);
        $spendingDTO->consume=($spendingDTO->consume*1000)+($lastSpending ? $lastSpending?->consume : 0);
        $spending=$this->spendingRepository->create($spendingDTO->toArray());
        return $spending;
    }

    public function update(int $id ,SpendingDTO $spendingDTO)
    {
        $spending=$this->spendingRepository->find($id);
        if (!$spending)
        {
            throw new ErrorException(__('spending.notFound'),ApiCode::NOT_FOUND);
        }
        $spending=$this->spendingRepository->update($spending,$spendingDTO->toArray());
        return $spending;
    }

    public function getAll(int $counter_id,Request $request)
    {
        $spendings=$this->spendingRepository->getAll($counter_id,[ 'date' => $request->query('date')]);
        return $spendings;
    }

    public function delete(int $id)
    {
        $spending=$this->spendingRepository->find($id);
        if (!$spending)
        {
            throw new ErrorException(__('spending.notFound'),ApiCode::NOT_FOUND);
        }
        return $this->spendingRepository->delete($spending);
    }

    public function getDays($counter_id)
    {
        $days=$this->spendingRepository->getDays($counter_id);
        return $days;
    }
}
