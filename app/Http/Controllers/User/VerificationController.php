<?php

namespace App\Http\Controllers\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Controller;
use App\Services\User\VerificationService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __construct(
        protected VerificationService $verificationService
    ) {}

    public function verify(Request $request, $id, $hash)
    {
       $data= $this->verificationService->verifyUser($id, $hash);
       return view("verified");
    }

    public function send(Request $request)
    {
     $this->verificationService->sendVerificationEmail($request->user());
        return ApiResponses::success(null,__('verification.send_verification'),ApiCode::OK);
    }

    public function resend(Request $request){
       $data= $this->verificationService->resendVerificationEmail($request->user());
        return ApiResponses::success(null,__('verification.resend'),ApiCode::OK);
    }
}
