<?php

namespace App\Http\Controllers;

use App\DTOs\ProfileDTO;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService  $accountService,
    )
    {
    }
    public function getProfile()
    {
        return $this->accountService->getProfile();
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $profileDTO=ProfileDTO::fromRequest($request);
        return $this->accountService->updateProfile($profileDTO);
    }
    public function blocking($user_id)
    {
        return $this->accountService->blocking($user_id);
    }

    public function getAll(Request $request)
    {
        return $this->accountService->getAll([$request->query()]);

    }
}
