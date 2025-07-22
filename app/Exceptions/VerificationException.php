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
    public function render($request)
    {
        return ApiResponses::error($this->getMessage(), $this->getCode());
    }
}
