<?php

namespace App\Application\Feature\UseCases;

use App\Domain\Feature\DTOs\FeatureDTO;
use App\Domain\Feature\Repositories\FeatureRepositoryInterface;

class UpdateHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FeatureRepositoryInterface $featureRepository)
    {
        //
    }

    public function handle(int $id , FeatureDTO $featureDTO)
    {
        return $this->featureRepository->update($id,$featureDTO);
    }
}
