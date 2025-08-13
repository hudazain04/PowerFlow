<?php

namespace App\Services;

use AllowDynamicProperties;
use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponses;
use App\DTOs\UserDTO;
use App\Exceptions\AuthException;
use App\Exceptions\ErrorException;
use App\Exceptions\VerificationException;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailJob;
use App\Models\Employee;
use App\Models\PowerGenerator;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\interfaces\UserRepositoryInterface;
use App\Services\User\VerificationService;
use App\Types\UserTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use PhpParser\Node\Expr\Throw_;
use \Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class UserService
{
//   protected $authRepository;
    public function __construct(private UserRepositoryInterface $authRepository,
    protected VerificationService $verificationService,
    ){
//        return $this->authRepository=$authRepository;

    }

//    public function register(array $dto,string $role){
//
//            $data=UserDTO::from($dto);
//
//            $exist= $this->authRepository->findUserByEmail($data->email);
//            if ($exist){
//                throw  AuthException::emailExists();
//            }
//            DB::beginTransaction();
//            try {
//                $user = $this->authRepository->createUser($data);
//                $this->authRepository->assignRole($user, $role);
//                DB::commit();
//                $userData=new UserResource($user);
//                //         $this->verification->sendVerificationEmail($user);
//                SendEmailJob::dispatchAfterResponse($user);
//                //         $event=
//                //        $token=JWTAuth::fromUser($user);
//                $result= $userData;
//                return ApiResponses::success($result,__('messages.user_registered'),ApiCode::OK);
//            } catch (\Throwable $exception) {
//                DB::rollBack();
//                throw AuthException::ServerError();
//            }
//
//
//
//
//    }
//
//
//    public function login(LoginRequest $request)
//    {
//            $credintials = $request->only('email', 'password');
//
//            if (!$token = JWTAuth::attempt($credintials)) {
//                throw AuthException::invalidCredentials();
//            }
//
//            $user = $this->findUser($request->email);
//            if (is_null($user->email_verified_at)) {
//                SendEmailJob::dispatchAfterResponse($user);
////                $this->verificationService->sendVerificationEmail($user);
////                throw VerificationException::emailNotVerfied(['verified'=>false]);
//                throw  new ErrorException(__('messages.error.notVerified'),ApiCode::UNAUTHORIZED,['verified'=>false,'user'=>$user]);
//            }
//            $User = UserResource::make($user);
//            $result = ["user" => $User, "token" => $token];
//            return ApiResponses::success($result, __('messages.login_success'), ApiCode::OK);
//    }
    public function register(array $dto){

        $data=UserDTO::from($dto);

        $exist= $this->authRepository->findUserByEmail($data->email);
        if ($exist){
            throw  AuthException::emailExists();
        }
        DB::beginTransaction();
        try {
            $user = $this->authRepository->createUser($data);
            $this->authRepository->assignRole($user, UserTypes::USER);
            DB::commit();
            return $user;
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw AuthException::ServerError();
        }


    }
    public function update(int $id,array $data){

        $user=$this->authRepository->findById($id);
        if (!$user){
            throw AuthException::usernotExists();
        }
        return $this->authRepository->update($user,$data);
    }

    public function delete(int $id){
        $user=$this->authRepository->findById($id);
        if (!$user){
            throw AuthException::usernotExists();
        }
        return $this->authRepository->delete($user);
    }
    public function findUser(string $email){
        $user=$this->authRepository->findUserByEmail($email);
        return $user;
    }

}

