<?php

namespace App\Repositories\interfaces\SuperAdmin;

use Illuminate\Support\Collection;
use App\DTOs\FeatureDTO;
use App\Models\Feature as FeatureModel;

interface FeatureRepositoryInterface
{
    public function all() : Collection;

    public function find(int $id) : FeatureModel;

    public function create(array $data) : FeatureModel;

    public function update(FeatureModel $feature , array $data) : FeatureModel;

    public function delete(FeatureModel $feature) : bool;
}
