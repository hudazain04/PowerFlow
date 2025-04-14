<?php

namespace App\Application\Feature\UseCases;

use App\Domain\Feature\DTOs\FeatureDTO;
use App\Domain\Feature\Repositories\FeatureRepositoryInterface;

class CreateHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FeatureRepositoryInterface $featureRepository)
    {
        //
    }
    public function handle(FeatureDTO $featureDTO)
    {
        return $this->featureRepository->create($featureDTO);
    }
}
