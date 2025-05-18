<?php

namespace App\Services\SuperAdmin;

use App\DTOs\GeneratorDTO;
use App\Events\GeneratorApproved;
use App\Events\GeneratorRejected;
use App\Events\NewGeneratorRequest;
use App\Models\PowerGenerator;
use App\Repositories\interfaces\SuperAdmin\GeneratorRequestRepositoryInterface;
use App\Types\GeneratorRequests;
use Illuminate\Support\Facades\DB;

class GeneratorRequestService
{
    public function __construct(
        protected GeneratorRequestRepositoryInterface $repository
    ){}

    public function createRequest(GeneratorDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            $request=$this->repository->create($dto->toArray());
            event(new NewGeneratorRequest($request));
            return $request;
        });
    }

    public function approveRequest(int $id)
    {
        return DB::transaction(function () use ($id) {
            $request = $this->repository->find($id);

            $this->repository->update($id, [
                'status' => GeneratorRequests::APPROVED,
            ]);

            $generator = PowerGenerator::create([
                'name' => $request->generator_name,
                'location' => $request->generator_location,
                'user_id' => $request->user_id

            ]);

            event(new GeneratorApproved($request->user_id, $generator));

            return $generator;
        });
    }

    public function rejectRequest(int $id)
    {
        return DB::transaction(function () use ($id) {
            $request = $this->repository->find($id);

            $this->repository->update($id, [
                'status' => GeneratorRequests::REJECTED,
            ]);

            event(new GeneratorRejected($request->user_id, $request->generator_name));

            return true;
        });
    }

    public function getPendingRequests()
    {
        return $this->repository->getPendingRequests();
    }
}
