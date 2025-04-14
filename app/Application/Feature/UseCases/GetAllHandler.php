<?php

namespace App\Application\Feature\UseCases;

use App\Domain\Feature\Repositories\FeatureRepositoryInterface;

class GetAllHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FeatureRepositoryInterface $featureRepository)
    {
        //
    }

    public function handle()
    {
        return $this->featureRepository->all();
    }
}
