<?php

namespace App\Services\User;

use App\ApiHelper\ApiCode;
use App\DTOs\CounterDTO;
use App\DTOs\CustomerRequestDTO;
use App\Exceptions\ErrorException;
use App\Models\Counter;
use App\Models\CustomerRequest;
use App\Repositories\interfaces\Admin\CounterBoxRepositoryInterface;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\ElectricalBoxRepositoryInterface;
use App\Repositories\interfaces\User\CustomerRequestRepositoryInterface;
use App\Types\GeneratorRequests;
use Illuminate\Support\Facades\DB;

class CustomerRequestService
{
    public function __construct(
        private CustomerRequestRepositoryInterface $requestRepository,
        protected ElectricalBoxRepositoryInterface $electricalBoxRepository,
        protected CounterBoxRepositoryInterface $counterBoxRepository,

    ) {}

    public function createRequest(CustomerRequestDTO $dto)
    {
        $capacity=$this->electricalBoxRepository->find($dto->box_id)?->capacity;
        $counters=$this->counterBoxRepository->counterCount($dto->box_id);
        if (! $capacity > $counters)
        {
            throw new ErrorException(__('customerRequest.noCapacity'),ApiCode::BAD_REQUEST);
        }
        $request =$this->requestRepository->createRequest($dto->toArray());
        $request=$this->requestRepository->getWithRelations($request,['user','box']);
        return $request;
    }
    public function approveRequest(int $id , CustomerRequestDTO $requestDTO)
    {
        return DB::transaction(function () use ($id , $requestDTO){

          $request=$this->requestRepository->find($id);
          $requestDTO->status=GeneratorRequests::APPROVED;
          $this->requestRepository->update($id,$requestDTO->toArray());
          $counter = Counter::create([
              'user_id'=>$request->user_id,
              'number'=>1,
              'QRCode'=>'ff',
              'current_spending'=> 0,

          ]);
          return $counter;
        });
    }

    public function rejectRequest(int $id ,CustomerRequestDTO $requestDTO)
    {
        return DB::transaction(function () use($id,$requestDTO){
            $request=$this->requestRepository->find($id);
            $requestDTO->status=GeneratorRequests::REJECTED;
           $reject= $this->requestRepository->update($id,$requestDTO->toArray());
           return $reject;
        });

    }

    public function getPendingRequests(int $generatorId)
    {
        return $this->requestRepository->getPending($generatorId);
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
