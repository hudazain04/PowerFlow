<?php

namespace App\Services;

use AllowDynamicProperties;
use App\DTOs\UserDTO;
use App\Exceptions\AuthException;
use App\Models\Employee;
use App\Models\PowerGenerator;
use App\Models\User;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\interfaces\AuthRepositoryInterface;
use App\Types\UserTypes;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Throw_;
use \Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
   protected $authRepository;
    public function __construct(AuthRepositoryInterface $authRepository){
        return $this->authRepository=$authRepository;
    }

      public function register(UserDTO $dto,$RoleName){


          if( ! in_array($RoleName,UserTypes::$statuses)){
            throw AuthException::invalidRole();

          }
//          $role=Role::where('name',$RoleName)->first();
//              if(!$role){
//                  throw AuthException::roleNotFound();
//              }
              if($this->authRepository->findUserByEmail($dto->email)){
                  throw AuthException::emailExists();
              }
             $userdata=$dto->toArray();
          $userdata['password'] = Hash::make($userdata['password']);
              $user=$this->authRepository->createUser($userdata);
          $user->assignRole($RoleName);

          if($RoleName === UserTypes::ADMIN){
              PowerGenerator::create([
                  'name'=>$user->name,
                  'location'=>'',
                  'user_id'=>$user->id
              ]);

          }elseif ($RoleName === UserTypes::EMPLOYEE){
              if(empty($dto->generator_id)){
                  throw AuthException::missingGeneratorId();
              }
              $secret=Str::random(8);
              Employee::create([
                  'phone_number' => $dto->phone_number,
                  'first_name' => $dto->first_name,
                  'last_name' => $dto->last_name,
                  'generator_id' => $dto->generator_id,
                  'secret_key' => $secret,
                  'password' => $user->password,
                  'user_id'      => $user->id,
              ]);
              $user->secret_Key=$secret;

              $token=JWTAuth::fromUser($user);
              return [
                  'token'=>$token,
                  'user'=>$user
              ];
          }
          $token = JWTAuth::fromUser($user);

          return [
              'token' => $token,
              'user' => $user
          ];

      }

    public function login(string $email, string $password, ?string $secretKey = null): array
    {
        $user = $this->authRepository->findUserByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw AuthException::invalidCredentials();
        }

        // If the user is an employee, validate the secret key
        if ($user->hasRole('employee')) {
            if (empty($secretKey)) {
                throw AuthException::missingSecretKey();
            }

            $employee = Employee::where('user_id', $user->id)->first();


            if (!$employee || $employee->secret_key !== $secretKey) {
                throw AuthException::invalidSecretKey();
            }
        }

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

}

