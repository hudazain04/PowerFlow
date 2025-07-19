<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
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
use App\Services\User\VerificationService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function __construct(private UserService $authservice,
    private VerificationService $verification)
    {

    }

     public function register(UserRequest $request){

        $user=$this->authservice->register($request->validated(),$request->role);
         $userData=new UserResource($user);
         $not=$this->verification->sendVerificationEmail($user);
//        $token=JWTAuth::fromUser($user);
        $result= $userData;
        return ApiResponses::success($result,__('messages.user_registered'),ApiCode::OK);

     }

     public function login (LoginRequest $request){

       $credintials = $request->only('email','password');

       if (!$token=JWTAuth::attempt($credintials)){
           throw AuthException::invalidCredentials();
       }
         $user=$this->authservice->findUser($request->email);
       $User=UserResource::make($user);
       $result=["user:"=>$User,"token:"=>$token];
       return ApiResponses::success($result, __('messages.login_success'), ApiCode::OK);

     }
     public function logout(){
         JWTAuth::invalidate(JWTAuth::getToken());
         return ApiResponses::success(null, __('messages.logout'),ApiCode::OK);
     }





}
