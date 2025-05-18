<?php

namespace App\Repositories\interfaces\SuperAdmin;

use App\DTOs\GeneratorDTO;
use App\Models\GeneratorRequest;
use App\Types\GeneratorRequests;

interface GeneratorRequestRepositoryInterface
{
    public function create(array $data): GeneratorRequest;
    public function find(int $id): ?GeneratorRequest;
    public function update(int $id, array $data): bool;
    public function getPendingRequests();
    public function getByStatus(string $status);
}
