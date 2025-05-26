<?php

namespace App\Services\User;

use App\DTOs\CounterDTO;
use App\DTOs\CustomerRequestDTO;
use App\Models\Counter;
use App\Models\CustomerRequest;
use App\Repositories\interfaces\User\CustomerRequestRepositoryInterface;
use App\Types\GeneratorRequests;
use Illuminate\Support\Facades\DB;

class CustomerRequestService
{
    public function __construct(
        private CustomerRequestRepositoryInterface $requestRepository,

    ) {}

    public function createRequest(CustomerRequestDTO $dto)
    {
        return $this->requestRepository->createRequest(
         array_merge(['user_id'=>auth()->id()],$dto->toArray())

        );
    }
    public function approveRequest(int $id)
    {
        return DB::transaction(function () use ($id){

          $request=$this->requestRepository->find($id);

          $this->requestRepository->update($id,[
              'status'=>GeneratorRequests::APPROVED,
          ]);
          $counter = Counter::create([
              'user_id'=>$request->user_id,
              'number'=>1,
              'QRCode'=>'ff',
              'current_spending'=> 0,

          ]);
          return $counter;
        });
    }

    public function rejectRequest(int $id)
    {
        return DB::transaction(function () use($id){
            $request=$this->requestRepository->find($id);
           $reject= $this->requestRepository->update($id,[
                'status'=>GeneratorRequests::REJECTED,
            ]);
           return $reject;
        });

    }

    public function getPendingRequests(int $generatorId)
    {
        return $this->requestRepository->getGeneratorRequests($generatorId);
    }

    private function getRequestUserId(int $requestId): int
    {
        return CustomerRequest::findOrFail($requestId)->user_id;
    }

    private function generateQRCode(): string
    {
        return 'CNTR-' . uniqid() . '-' . rand(1000, 9999);
    }





















}
