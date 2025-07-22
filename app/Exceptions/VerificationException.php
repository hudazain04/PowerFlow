<?php

namespace App\Exceptions;

use App\ApiHelper\ApiResponse;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Admin\serSubController;
use Exception;

class VerificationException extends Exception
{

    public static function emailVerified(){
        return new self('Email already verified',400);
    }
    public static function invalidLink(){
        return new self('Invalid verification link',403);
    }
    public static function emailNotVerfied(){
        return new self('email registered but did not verify the account',401 );
    }
    public static function userNotFound(){
        return new self('user not found',401);
    }
    public function render($request)
    {
        return ApiResponses::error($this->getMessage(), $this->getCode());
    }
}
