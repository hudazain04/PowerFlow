<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\UserDTO;
use App\Events\UserApproved;
use App\Events\UserRegistered;
use App\Events\UserVerified;
use App\Exceptions\AuthException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Area;
use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\AccountRejectedNotification;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct(private UserService $authservice)
    {
    }

     public function register(UserRequest $request){

        $user=$this->authservice->register($request->validated(),$request->role);
         $userData=new UserResource($user);
        $token=JWTAuth::fromUser($user);
        $result=[...[$userData,'token'=>$token]];
        return ApiResponse::success($result,__('messages.user_registered'),ApiCode::OK);

     }

     public function login (LoginRequest $request){

       $credintials = $request->only('email','password');

       if (!$token=JWTAuth::attempt($credintials)){
           throw AuthException::invalidCredentials();
       }
       return ApiResponse::success($token, __('messages.login_success'), ApiCode::OK);

     }
     public function logout(){
         JWTAuth::invalidate(JWTAuth::getToken());
         return ApiResponse::success(null, __('messages.logout'),ApiCode::OK);
     }


    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->isVerified()) {
            return ApiResponse::error('email already verified',400);
        }

        if (now()->gt($user->verification_code_expires_at)) {
            return ApiResponse::error('expired',400);
        }


        $user->update([

            'verification_code' => null,
            'verification_code_expires_at' => null,
            'verified'=>true
        ]);

        return response()->json(['message' => 'Email verified successfully']);
    }


    public function approve(User $user)
    {
        if ($user->isApproved()) {
            return response()->json(['message' => 'User already approved'], 400);
        }

        $user->update([
            'approved' => true

        ]);

        $user->notify(new AccountApprovedNotification());

        return response()->json(['message' => 'User approved successfully']);
    }

    public function reject(User $user)
    {
        $user->notify(new AccountRejectedNotification());
        $user->delete();

        return response()->json(['message' => 'User rejected ']);
    }


}
