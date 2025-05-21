<?php

namespace App\Repositories\interfaces\SuperAdmin;
use Illuminate\Support\Collection;
use App\Models\Visitor as VisitorModel;


interface VisitorRepositoryInterface
{
    public function count() : int;

    public function dailyAvg() : int;
}
