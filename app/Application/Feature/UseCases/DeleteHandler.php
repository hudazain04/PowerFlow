<?php

namespace App\Application\Feature\UseCases;

use App\Domain\Feature\Repositories\FeatureRepositoryInterface;

class DeleteHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected FeatureRepositoryInterface $featureRepository)
    {
        //
    }

    public function handle(int $id)
    {
        return $this->featureRepository->delete($id);
    }
}
