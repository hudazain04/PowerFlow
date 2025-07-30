<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\UserDTO;
use App\Events\UserApproved;
use App\Events\UserRegistered;
use App\Events\UserVerified;
use App\Exceptions\AuthException;
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

     public function register(UserRequest $request)
     {
        return $this->authservice->register($request->validated(),UserTypes::USER);
     }

     public function login (LoginRequest $request)
     {
         return $this->authservice->login($request);
     }
     public function logout(){
         JWTAuth::invalidate(JWTAuth::getToken());
         return ApiResponses::success(null, __('messages.logout'),ApiCode::OK);
     }





}
