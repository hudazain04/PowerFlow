<?php

namespace App\Exceptions;

use App\ApiHelper\ApiResponse;
use Exception;

class AuthException extends Exception
{
    public static function invalidRole(): self
    {
        return new self('The specified role is invalid.', 400);
    }

    public static function roleNotFound(): self
    {
        return new self('The specified role does not exist.', 400);
    }

    public static function emailExists(): self
    {
        return new self('The email address is already registered.', 400);
    }

    public static function invalidCredentials(): self
    {
        return new self('The provided credentials are invalid.', 401);
    }

    public static function missingSecretKey(): self
    {
        return new self('A secret key is required for employee login.', 400);
    }

    public static function invalidSecretKey(): self
    {
        return new self('The provided secret key is invalid.', 401);
    }

    public static function missingGeneratorId(): self
    {
        return new self('A generator ID is required for employee registration.', 400);
    }

    public function render($request)
    {
        return ApiResponse::error($this->getMessage(), $this->getCode());
    }
}
