<?php

namespace App\Repositories\interfaces\SuperAdmin;

use Illuminate\Support\Collection;
use App\DTOs\FeatureDTO;

interface FeatureRepositoryInterface
{
    public function all() : Collection;

    public function find(int $id) : FeatureDTO;

    public function create(FeatureDTO $featureDTO) : FeatureDTO;

    public function update(FeatureDTO $feature , FeatureDTO $featureDTO) : FeatureDTO;

    public function delete(FeatureDTO $featureDTO) : bool;
}
