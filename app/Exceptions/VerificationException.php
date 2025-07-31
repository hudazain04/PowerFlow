<?php

namespace App\Exceptions;

use App\ApiHelper\ApiResponse;
use App\ApiHelper\ApiResponses;
use App\Http\Controllers\Admin\serSubController;
use Exception;
use Throwable;

class VerificationException extends Exception
{
    public $data;
    public function __construct($message = "", $code = 500,  $data)
    {
        parent::__construct($message, $code);
        $this->data=$data;
    }

    public static function emailVerified($data=null){
        return new self('Email already verified',400,$data);
    }
    public static function invalidLink($data=null){
        return new self('Invalid verification link',403,$data);
    }
    public static function emailNotVerfied($data=null){
        return new self('email registered but did not verify the account',401,$data );
    }
    public static function userNotFound($data=null){
        return new self('user not found',401,$data);
    }
    public function render($request)
    {
        return ApiResponses::error($this->getMessage(), $this->getCode() , $this->data);
    }
}
