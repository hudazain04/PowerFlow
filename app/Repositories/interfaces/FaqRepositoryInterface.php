<?php

namespace App\Repositories\interfaces;

use App\DTOs\FaqDTO;
use App\Models\Faq;

interface FaqRepositoryInterface
{

    public function getFaqByRole(string $category);
    public function findFaq(int $id) : ?Faq ;
    public function createFaq(FaqDTO $dto) : ?Faq ;
    public function update(Faq $faq,array $data) : ?Faq ;
    public function delete (Faq $faq) : bool ;




}
