<?php

namespace App\Http\Controllers\SuperAdmin;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\FaqDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Models\User;
use App\Services\FaqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FaqController extends Controller
{
    use AuthorizesRequests;


//        $user = \request()->user();
//        $role = $user->getRoleNames()->first();

 public function __construct(private FaqService $faqService){
 }
     public function getFaqByRole(string $role){
       $faq = $this->faqService->getFaqByRole($role);
         $faqData=FaqResource::collection($faq);
       return ApiResponses::success($faqData,__('Faqmessages.Faq'),ApiCode::OK);
     }

     public function createFaq(FaqRequest $request){
        $dto = FaqDTO::from($request->validated());
        return $this->faqService->createFaq($dto);
     }

     public function updateFaq(int $id,FaqRequest $request){
       $faq = $this->faqService->update($id,$request->validated());
         $faqData=FaqResource::make($faq);
       return ApiResponses::success($faqData,__('Faqmessages.Faq_updated'),ApiCode::OK);
     }

     public function deleteFaq(int $id){
      $faq = $this->faqService->delete($id);
      return ApiResponses::success($faq,__('Faqmessages.Faq_deleted'),ApiCode::OK);
     }

}
