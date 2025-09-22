<?php

namespace App\Http\Controllers;

use App\ApiHelper\ApiResponse;
use App\DTOs\ProfileDTO;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\LandingProfileResource;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected AccountService  $accountService,
    )
    {
    }
    public function getProfile()
    {
        return $this->accountService->getProfile();
    }

    public function getLandingProfile()
    {
        $data= $this->accountService->getLandingProfile();
        return  $this->success(LandingProfileResource::make($data),__('messages.success'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $profileDTO=ProfileDTO::fromRequest($request);
        return $this->accountService->updateProfile($profileDTO);
    }
    public function blocking($generator_id)
    {
        return $this->accountService->blocking($generator_id);
    }

    public function getAll(Request $request)
    {
        return $this->accountService->getAll([$request->query()]);

    }
}
