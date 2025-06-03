<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService  $accountService,
    )
    {
    }
    public function blocking($user_id)
    {
        return $this->accountService->blocking($user_id);
    }
}
