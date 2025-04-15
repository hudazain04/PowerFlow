<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use App\DTOs\UserDTO;
use App\Http\Requests\UserRequest;
use App\Models\Area;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authservice;
    public function __construct(AuthService $authservice)
    {
        $this->authservice=$authservice;
    }

    public function register(UserRequest $request){
        $data=$request->validated();

        $roleName = $data['role'];


        $userData = array_diff_key($data, array_flip(['role']));
        $userDTO = new UserDTO(...$userData);

        $result = $this->authservice->register($userDTO, $roleName);

        return ApiResponse::success($result, 'User registered successfully', ApiCode::CREATED);
    }
    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
                'secret_key' => 'nullable|string',
            ]);
//            logger($data);
            $result = $this->authservice->login(
                $data['email'],
                $data['password'],
                array_key_exists('secret_key', $data) ? $data['secret_key'] : null
            );

            return ApiResponse::success($result, 'Login successful');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 401);
        }
    }

    public function logout()
    {
        $this->authservice->logout();
        return ApiResponse::success([], 'Logged out successfully');
    }

}
