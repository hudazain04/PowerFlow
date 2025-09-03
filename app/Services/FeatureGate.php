<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\interfaces\Admin\CounterRepositoryInterface;
use App\Repositories\interfaces\Admin\PowerGeneratorRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\PlanRepositoryInterface;
use App\Repositories\interfaces\SuperAdmin\SubscriptionRepositoryInterface;

class FeatureGate
{
    public function __construct(
        protected SubscriptionRepositoryInterface  $subscriptionRepository,
        protected PlanRepositoryInterface $planRepository,
        protected PowerGeneratorRepositoryInterface $powerGeneratorRepository,
        protected CounterRepositoryInterface $counterRepository,
    )
    {
    }

    public function check(int $generator_id, string $featureKey, int $increment = 1): bool
    {
        $subscription = $this->subscriptionRepository->getLastForGenerator($generator_id);

        if (!$subscription) {
            return false;
        }

        $plan = $this->subscriptionRepository->getRelations($subscription,['planPrice.plan'])->planPrice->plan;

        $feature = $this->planRepository->getFeatures($plan,['key'=>$featureKey]);

        if (!$feature) {
            return false;
        }

        if ($feature->pivot->value == -1) {
            return true;
        }

        $currentUsage = $this->getCurrentUsage($generator_id, $featureKey);

        return ($currentUsage + $increment) <= $feature->pivot->value;
    }

    protected function getCurrentUsage(int $generator_id, string $featureKey): int
    {
        $generator=$this->powerGeneratorRepository->find($generator_id);

        return match ($featureKey) {
            'users_count' => $this->counterRepository->getUserCount($generator_id),
            'counters_count' =>$this->powerGeneratorRepository->getRelationCount($generator,'counters'),
            'neighborhoods_count'=>$this->powerGeneratorRepository->getRelationCount($generator,'areas'),
            'boxes_count'=>$this->powerGeneratorRepository->getRelationCount($generator,'electricalBoxes'),
            'employees_count'=>$this->powerGeneratorRepository->getRelationCount($generator,'employees'),


            default => 0
        };
    }
}
