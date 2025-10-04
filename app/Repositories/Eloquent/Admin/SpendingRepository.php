<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Spending as SpendingModel;
use App\Repositories\interfaces\Admin\SpendingRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SpendingRepository implements SpendingRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(array $data): SpendingModel
    {
        $spending=SpendingModel::create($data);
        return $spending;
    }

    public function update(SpendingModel $spending, array $data): SpendingModel
    {
        $spending->update($data);
        $spending->save();
        return $spending;
    }

    public function find(int $id): ?SpendingModel
    {
        $spending=SpendingModel::find($id);
        return  $spending;
    }

    public function delete(SpendingModel $spending): bool
    {
        return $spending->delete();
    }

    public function getAll(int $counter_id ,?array  $filters=[]): LengthAwarePaginator
    {
        $spendings=SpendingModel::where('counter_id',$counter_id)->paginate(10);
        return $spendings;
    }

    public function getLastForCounter(int $counter_id): ?SpendingModel
    {
        $spending=SpendingModel::where('counter_id',$counter_id)->latest()->first();
        return $spending;
    }

    public function getDays(int $counter_id)
    {
        $days = SpendingModel::where('counter_id',$counter_id)
            ->select('date')
            ->distinct()
            ->get()
            ->pluck('date')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->toDateString());
        return $days;
    }
}
