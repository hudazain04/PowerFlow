<?php

namespace App\Services\Admin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\GeneratorSettingDTO;
use App\DTOs\PowerGeneratorDTO;
use App\DTOs\SubscribedGeneratorDTO;
use App\DTOs\SubscriptionDTO;
use App\Exceptions\ErrorException;
use App\Http\Resources\PowerGeneratorResource;
use App\Http\Resources\SubscribedGeneratorResource;
use App\Repositories\interfaces\Admin\GeneratorSettingRepositoryInterface;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;

class PowerGeneratorService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected PowerGeneratorRepositoryInterface $powerGeneratorRepository,
        protected GeneratorSettingRepositoryInterface $generatorSettingRepository,
    )
    {
        //
    }

    public function getForPlan(int $plan_id ,  array $filters)
    {
        $generators=$this->powerGeneratorRepository->getForPlan($plan_id,$filters);
        $generatorsDTOs=$generators->map(function ($generator){
            $generatorDTO=SubscribedGeneratorDTO::fromModel($generator);
            $generatorDTO->phone=$generator->user->phone_number;
            $generatorDTO->expired_at=$generator->subscriptions->first()->start_time->addMonths($generator->subscriptions->first()->period);
//            dd(gettype($generator->subscriptions->first()->start_time));
            return $generatorDTO;
        });
        return $this->successWithPagination(SubscribedGeneratorResource::collection($generators),__('messages.success'));
    }

    public function getAll(array $filters)
    {
        $generators=$this->powerGeneratorRepository->getAll($filters);
//        $generators->getCollection()->transform(function ($generator){
//            $generatorDTO=PowerGeneratorDTO::fromModel($generator);
//            $generatorDTO->phone=$generator->user->phone_number;
//            $generatorDTO->email=$generator->user->email;
//            $generatorDTO->expired_at=$generator->subscriptions->first()->start_time->addMonths($generator->subscriptions->first()->period);
////            dd(gettype($generator->subscriptions->first()->start_time));
//            return $generatorDTO;
//        });
//        dd($generatorsDTOs);
        return $this->successWithPagination(PowerGeneratorResource::collection($generators),__('messages.success'));
    }

    public function updateInfo($generator_id ,  PowerGeneratorDTO $generatorDTO,GeneratorSettingDTO $generatorSettingDTO)
    {
        $generator=$this->powerGeneratorRepository->find($generator_id);
        if (! $generator)
        {
            throw  new ErrorException(__('powerGenerator.notFound'),ApiCode::NOT_FOUND);
        }
        $generator=$this->powerGeneratorRepository->update($generator,$generatorDTO->toArray());
        $generator->syncPhones($generatorDTO->phones);
        $generatorSetting=$generator->settings;
        if (! $generatorSetting)
        {
            throw new ErrorException(__('powerGenerator.noSetting'),ApiCode::NOT_FOUND);
        }
//        dd($generatorSettingDTO);
        $this->generatorSettingRepository->update($generatorSetting,$generatorSettingDTO->toArray());
        return $generator;
    }

}
