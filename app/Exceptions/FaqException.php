<?php

namespace App\Exceptions;
use App\ApiHelper\ApiResponse;
use Exception;
class FaqException extends Exception
{

    public static function FaqNotFound() : self{
        return new self('faq not found',400);
    }

    public function render($request)
    {
        return ApiResponse::error($this->getMessage(), $this->getCode());
    }
}
