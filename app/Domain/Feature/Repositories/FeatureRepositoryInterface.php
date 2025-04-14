<?php

namespace App\Domain\Feature\Repositories;

use App\Domain\Feature\DTOs\FeatureDTO;
use Illuminate\Support\Collection;

interface FeatureRepositoryInterface
{
    public function all() : Collection;

    public function find(int $id) : FeatureDTO;

    public function create(FeatureDTO $featureDTO) : FeatureDTO;

    public function update(int $id , FeatureDTO $featureDTO) : FeatureDTO;

    public function delete(int $id) : bool;


}
