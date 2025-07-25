<?php

namespace App\Repositories\Eloquent\User;

use App\Models\Complaint as ComplaintModel;
use App\Repositories\interfaces\User\ComplaintRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(array $data): ComplaintModel
    {
        $complaint=ComplaintModel::create($data);
        return $complaint;
    }

    public function update(ComplaintModel $complaint, array $data): ComplaintModel
    {
        $complaint->update($data);
        $complaint->save();
        return $complaint;
    }


    public function find(int $id): ?ComplaintModel
    {
        $complaint=ComplaintModel::find($id);
        return $complaint;
    }

    public function delete(ComplaintModel $complaint): bool
    {
        return $complaint->delete();
    }

    public function getAll(?array $filters=[]): Collection
    {
//        dd($filters);
        $complaints=ComplaintModel::filter($filters)->get();
        return $complaints;
    }
}
