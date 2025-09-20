<?php

namespace App\Services\SuperAdmin;

use App\DTOs\GeneratorDTO;
use App\Events\GeneratorApproved;
use App\Events\GeneratorRejected;
use App\Events\NewGeneratorRequest;
use App\Exceptions\AuthException;
use App\Models\GeneratorRequest;
use App\Models\PowerGenerator;
use App\Models\User;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\GeneratorRequestRepositoryInterface;
use App\Types\GeneratorRequests;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class GeneratorRequestService
{
    public function __construct(
        protected GeneratorRequestRepositoryInterface $repository,
        protected PowerGeneratorRepositoryInterface $generatorRepository
    ){}

    public function createRequest(GeneratorDTO $dto)
    {
        $userId=auth()->user()->id;
        return DB::transaction(function () use ($dto,$userId) {
            $existingRequest = GeneratorRequest::where('user_id', $userId)
                ->where('status', GeneratorRequests::PENDING)
                ->first();

            if ($existingRequest) {
                throw AuthException::alreadySent();
            }
                $request=$this->repository->create( $dto->createArray());
                event(new NewGeneratorRequest($request));
                return $request;
            }

        );
    }

    public function approveRequest(int $id)
    {
        return DB::transaction(function () use ($id) {
            $request = $this->repository->find($id);

            $this->repository->update($id, [
                'status' => GeneratorRequests::APPROVED,
            ]);

            $generator=$this->generatorRepository->create([
                'name' => $request->generator_name,
                'location' => $request->generator_location,
                'user_id'=>$request->user_id
            ]);


            $user=$request->user;
            $user->syncRoles([]);
            $user->assignRole('admin');


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

    public function getGenInfo(int $generator_id){
        $generator = $this->repository->getGenInfo($generator_id);

        return [
            'name' => $generator->name,
            'phone' => $generator->phones->first()->number,
            'email' => $generator->user->email,
            'location' => $generator->location,
            'date' => $generator->created_at->format('n/j/Y'),
            'current_plan' => $generator->subscriptions->first()->planPrice->plan->name ?? 'N/A',
            'current_plan_id'=>$generator->subscriptions->first()->planPrice->plan->id,
            'current_price' => $generator->subscriptions->first()->price?? 'ΘN/a'
        ];
    }

    public function delete(int $generator_id){
      $generator=PowerGenerator::find($generator_id);
        if(!$generator){
            throw AuthException::usernotExists();
        }
        $generator= $this->repository->delete($generator_id);
        return $generator;
    }
}
