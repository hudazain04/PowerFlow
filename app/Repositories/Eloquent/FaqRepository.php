<?php

namespace App\Repositories\Eloquent;

use App\DTOs\FaqDTO;
use App\Models\Faq;
use App\Repositories\interfaces\FaqRepositoryInterface;

class FaqRepository implements FaqRepositoryInterface
{
    protected $model;
    public function __construct(Faq $model){
       $this->model=$model;
    }

    public function getFaqByRole(string $role)
    {

        return $this->model->forCategory($role)->get();

    }

    public function createFaq(FaqDTO $dto): ?Faq
    {
        return $this->model->create($dto->toArray());
    }

    public function update(Faq $faq,array $data): ?Faq
    {
        $faq->update($data);
        return $faq;
    }

    public function delete(Faq $faq): bool
    {
       return $faq->delete();
    }

    public function findFaq(int $id): ?Faq
    {
        return $this->model->findorFail($id);
    }
}
