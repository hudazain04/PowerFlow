<?php

namespace App\Services\Admin;

use App\DTOs\AssignCounterToBoxDTO;
use App\DTOs\ElectricalBoxDTO;
use App\Models\ElectricalBox;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ElectricalBoxService
{

    public function __construct(
        private ElectricalBoxRepositoryInterface $boxRepository,
        private CounterRepositoryInterface $counterRepository
    ) {}

    public function createBox(ElectricalBoxDTO $dto)
    {
        return $this->boxRepository->create([
            'number' => $dto->number,
            'capacity' => $dto->maxCapacity
        ]);
    }

    public function assignCounterToBox(AssignCounterToBoxDTO $dto)
    {
//        if ($this->isBoxFull($dto->boxId)) {
//            throw new \Exception('Box has reached maximum capacity');
//        }

        return $this->boxRepository->assignCounter($dto->boxId, $dto->counterId);
    }

    private function isBoxFull(int $boxId): bool
    {
        $currentCount = DB::table('controller_dex')
            ->where('box_id', $boxId)
            ->whereNull('removed_at')
            ->count();

        $boxCapacity = ElectricalBox::find($boxId)->max_capacity;

        return $currentCount >= $boxCapacity;
    }
    public function getBoxCounters(int $boxId)
    {
        return $this->boxRepository->getBoxCounters($boxId);
    }

    public function getAvailableBoxes(int $generatorId)
    {
        return $this->boxRepository->getAvailableBoxes($generatorId);
    }
}
