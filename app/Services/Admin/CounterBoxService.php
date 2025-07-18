<?php

namespace App\Services\Admin;

use App\Repositories\Eloquent\Admin\CounterBoxRepository;
use App\Repositories\interfaces\Admin\CounterBoxRepositoryInterface;

class CounterBoxService
{
    public function __construct(
        private CounterBoxRepositoryInterface $repository
    ) {}

    public function assignCounter(int $counterId, int $boxId)
    {
        return $this->repository->assignCounterToBox($counterId, $boxId);
    }

    public function removeCounter(int $counterId, int $boxId)
    {
        return $this->repository->removeCounterFromBox($counterId, $boxId);
    }

    public function getCurrentBox(int $counterId)
    {
        return $this->repository->getCurrentBox($counterId);
    }

    public function getBoxCounters(int $boxId)
    {
        return $this->repository->getBoxCounters($boxId);
    }
}
