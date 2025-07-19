<?php

namespace App\Services;

use AllowDynamicProperties;
use App\DTOs\UserDTO;
use App\Exceptions\AuthException;
use App\Models\Employee;
use App\Models\PowerGenerator;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\interfaces\UserRepositoryInterface;
use App\Types\UserTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Exception;
use PhpParser\Node\Expr\Throw_;
use \Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class UserService
{
//   protected $authRepository;
    public function __construct(private UserRepositoryInterface $authRepository){
//        return $this->authRepository=$authRepository;

    }

    public function register(array $dto,string $role){

       DB::beginTransaction();
        try {
            $data=UserDTO::from($dto);

            $exist= $this->authRepository->findUserByEmail($data->email);
            if ($exist){
                throw  AuthException::emailExists();
            }
            $user=$this->authRepository->createUser($data);
            $this->authRepository->assignRole($user,$role);
            return $user;
        }
        catch (\Throwable $exception){
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


}

