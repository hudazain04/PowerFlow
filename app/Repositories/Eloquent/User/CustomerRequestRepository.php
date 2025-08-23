<?php

namespace App\Repositories\Eloquent\User;

use App\Models\CustomerRequest;
use App\Repositories\interfaces\User\CustomerRequestRepositoryInterface;
use App\Types\GeneratorRequests;

class CustomerRequestRepository implements CustomerRequestRepositoryInterface
{
    public function createRequest(array $data)
    {
        return CustomerRequest::create($data);
    }
    public function update(int $id, array $data) : bool
    {
       return CustomerRequest::where('id',$id)->update($data);
    }

  public function find(int $id)
  {
      return CustomerRequest::where('id',$id)->first();
  }

    public function getPending(int $generator_id)
    {
        return CustomerRequest::with('user')
            ->where('generator_id',$generator_id)
            ->where('status', GeneratorRequests::PENDING)
            ->with(['user','box'])
            ->latest()
            ->get();
    }

    public function getWithRelations(CustomerRequest $request ,  array $relations=['*']): CustomerRequest
    {
        $request->load($relations);
        return $request;
    }
}
