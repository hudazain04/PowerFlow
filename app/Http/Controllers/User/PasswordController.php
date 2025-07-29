<?php

namespace App\Http\Controllers\User;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\PasswordDto;
use App\DTOs\PasswordEmailDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\resendRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
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
          $user=User::where('email',$request->email)->first();
          $data=UserResource::make($user);

       return ApiResponses::success($data,__('password.request'),ApiCode::OK);
    }


    public function verify(Request $request)
    {
        $fullToken = $request->token;
        $cleanToken = explode('&', $fullToken)[0];

        $this->service->verify($cleanToken);

         return view('password');
    }


    public function reset(PasswordRequest $request)
    {
        $cleanToken = head(explode('&', $request->token));

        $dto = new PasswordDto(...[
            'token' => $cleanToken,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation
        ]);

        $this->service->resetPassword($dto);
       return ApiResponses::success(null,__('password.reset'),ApiCode::OK);
    }


    public function resend(resendRequest $request)
    {
        $email=$request->input('email');
        $dto = new PasswordEmailDTO($email);
        $this->service->sendLink($dto);

        return ApiResponses::success(null,__('password.resend'),ApiCode::OK);
    }
}
