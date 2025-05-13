<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
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
       return ApiResponse::success($faqData,__('Faqmessages.Faq'),ApiCode::OK);
     }

     public function createFaq(FaqRequest $request){
        $faq = $this->faqService->createFaq($request->validated());
        $faqData=FaqResource::make($faq);
        return ApiResponse::success($faqData,__('Faqmessages.Faq_created'),ApiCode::OK);
     }

     public function updateFaq(int $id,FaqRequest $request){
       $faq = $this->faqService->update($id,$request->validated());
         $faqData=FaqResource::make($faq);
       return ApiResponse::success($faqData,__('Faqmessages.Faq_updated'),ApiCode::OK);
     }

     public function deleteFaq(int $id){
      $faq = $this->faqService->delete($id);
      return ApiResponse::success($faq,__('Faqmessages.Faq_deleted'),ApiCode::OK);
     }

}
