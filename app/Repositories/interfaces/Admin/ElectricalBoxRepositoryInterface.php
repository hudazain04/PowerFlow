<?php

namespace App\Repositories\interfaces\Admin;

interface ElectricalBoxRepositoryInterface
{
    public function create(array $data);
    public function assignCounter(int $boxId, int $counterId);
    public function getBoxCounters(int $boxId);
    public function getAvailableBoxes(int $generatorId);
}
