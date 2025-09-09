<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\UserDTO;
use App\Events\UserApproved;
use App\Events\UserRegistered;
use App\Events\UserVerified;
use App\Exceptions\AuthException;
use App\Exceptions\ErrorException;
use App\Exceptions\VerificationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailJob;
use App\Models\Area;
use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\AccountRejectedNotification;
use App\Services\User\VerificationService;
use App\Services\UserService;
use App\Types\UserTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Psy\Readline\Hoa\Event;
use Tymon\JWTAuth\Facades\JWTAuth;
use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{


    public function __construct(private UserService $authservice,
    private VerificationService $verification)
    {

    }

//     public function register(UserRequest $request)
//     {
//        return $this->authservice->register($request->validated(),UserTypes::USER);
//     }
//
//     public function login (LoginRequest $request)
//     {
//         return $this->authservice->login($request);
//     }

        public function register(UserRequest $request){

            $user=$this->authservice->register($request->validated());
            $userData=new UserResource($user);
            SendEmailJob::dispatchAfterResponse($user);
//            $not=$this->verification->sendVerificationEmail($user);
            $event=
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
            if(is_null($user->email_verified_at)){
                SendEmailJob::dispatchAfterResponse($user);
//                $this->verification->sendVerificationEmail($user);
                throw new ErrorException(__('messages.error.notVerified'),ApiCode::UNAUTHORIZED,['verified'=>false,'user'=>$user]);
//                throw VerificationException::emailNotVerfied();
            }

            $user=$this->authservice->update($user->id,['fcmToken'=>$request->fcmToken]);

            $id=null;
            if($user->hasRole('admin')){
              $id=$user->powerGenerator->id;
            }
            $User=UserResource::make($user);
            $result=["user"=>$User,"token"=>$token,"power_generator"=>$id];
            return ApiResponses::success($result, __('messages.login_success'), ApiCode::OK);

        }

         public function logout(){
             JWTAuth::invalidate(JWTAuth::getToken());
             return ApiResponses::success(null, __('messages.logout'),ApiCode::OK);
         }





}
