<?php

namespace App\Http\Controllers\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\Exceptions\VerificationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\resendRequest;
use App\Models\User;
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

    public function resend(resendRequest $request){
        $email=$request->input('email');
        $user=User::where('email',$email)->first();

        if (!$user) {
            throw VerificationException::userNotFound(); // You might need to define this in VerificationException
            // OR return ApiResponses::error(__('messages.user_not_found'), ApiCode::NOT_FOUND);
        }

        // Call the service method with the User object
        $this->verificationService->resendVerificationEmail($user);
        return ApiResponses::success(null,__('verification.resend'),ApiCode::OK);
    }
}
