<?php

namespace App\Repositories\interfaces\User;

interface UserAppRepositoryInterface
{
    public function resetPassword(int $id,string $newPassword);
    public function name(int $id,array $name);
    public function getCounters(int $id);
    public function consumptionRate(int $id);
    public function getPayments(int $id);
    public function getCounter(int $id);
    public function getConsumption(int $counter_id,$startDate,$endDate);

}
