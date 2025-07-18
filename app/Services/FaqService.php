<?php

namespace App\Services;

use App\DTOs\FaqDTO;
use App\Exceptions\FaqException;
use App\Models\Faq;
use App\Repositories\interfaces\FaqRepositoryInterface;

class FaqService
{
  public function __construct(private FaqRepositoryInterface $faqRepository){}

    public function getFaqByRole(string $role){

        $faqs = $this->faqRepository->getFaqByRole($role);
        if($faqs->isEmpty()) {
            throw FaqException::FaqNotFound();
        }
        return $faqs;
    }

    public function createFaq(array $data){
      $dto = FaqDTO::from($data);
      return $this->faqRepository->createFaq($dto);
    }

    public function update(int $id , array $data){
      $faq=$this->faqRepository->findFaq($id);
      return $this->faqRepository->update($faq,$data);
    }

    public function delete(int $id){

        $faq = $this->faqRepository->findFaq($id);

      if(! $faq){
          throw FaqException::FaqNotFound();
      }

      return $this->faqRepository->delete($faq);
    }
}
