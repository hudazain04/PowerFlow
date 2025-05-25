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

    public function getPending()
    {
        return CustomerRequest::with('user')
            ->where('status', GeneratorRequests::PENDING)
            ->latest()
            ->get();
    }
}
