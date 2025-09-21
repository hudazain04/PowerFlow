<?php

namespace App\Services\SuperAdmin;

use App\DTOs\NeighborhoodDTO;
use App\Repositories\interfaces\SuperAdmin\NeighborhoodRepositoryInterface;

class NeighborhoodService
{
    public function __construct(
        private NeighborhoodRepositoryInterface $repository
    ) {}

    public function createNeighborhood(NeighborhoodDTO $dto)
    {
        return $this->repository->create($dto->toArray());
    }
    public function getNeighborhood(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getAllNeighborhoods()
    {
        return $this->repository->listAll();
    }
    public function updateNeighborhood(int $id, NeighborhoodDTO $dto)
    {
        return $this->repository->update($id, $dto->toArray());
    }

    public function deleteNeighborhood(int $id)
    {
        return $this->repository->delete($id);
    }


}
