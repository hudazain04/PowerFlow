<?php

namespace App\Exceptions;


use App\ApiHelper\ApiResponses;
use Exception;

class GeneralException extends Exception
{
    public static function boxes(){
        return new self('there is no generator with this id or he do not have boxes ',400);
    }
    public static function areas(){
        return new self('there is no generator with this id or he do not have areas ',400);
    }
    public static function counters(){
        return new self('there is no generator with this id or he do not have areas',400 );
    }
    public static function CounterPdf(){
        return new self('counter not found',400 );
    }

    public function render($request)
    {
        return ApiResponses::error($this->getMessage(), $this->getCode());
    }

}
