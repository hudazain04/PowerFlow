<?php

namespace App\Repositories\interfaces\Admin;

interface CounterBoxRepositoryInterface
{
  public function assignCounterToBox(int $counterId, int $boxId);
  public function removeCounterFromBox(int $counterId, int $boxId);
  public function getCurrentBox(int $counterId);
    public function getBoxCounters(int $boxId);
    public function counterCount(int $box_id) :  int;
}
