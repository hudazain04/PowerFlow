<?php

namespace App\Http\Controllers\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\PasswordDto;
use App\DTOs\PasswordEmailDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Services\User\PasswordResetService;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function __construct(
        protected PasswordResetService $service
    ) {}


    public function request(Request $request)
    {
        $dto = new PasswordEmailDTO($request->email);
         $this->service->sendLink($dto);

       return ApiResponse::success(null,__('password.request'),ApiCode::OK);
    }


    public function verify(Request $request)
    {
       $this->service->verify($request->token);

      return ApiResponse::success(null,__('password.verify'),ApiCode::OK);
    }


    public function reset(PasswordRequest $request)
    {
        $dto = new PasswordDto(...$request->validated());

        $this->service->resetPassword($dto);

       return ApiResponse::success(null,__('password.reset'),ApiCode::OK);
    }


    public function resend(Request $request)
    {
        $dto = new PasswordEmailDTO($request->email);
        $this->service->sendLink($dto);

        return ApiResponse::success(null,__('password.resend'),ApiCode::OK);
    }
}
