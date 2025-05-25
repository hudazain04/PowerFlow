<?php

namespace App\Exceptions;

use App\ApiHelper\ApiCode;
use App\ApiHelper\ApiResponse;
use Exception;
use Throwable;

class ErrorException extends Exception
{
    use ApiResponse;
    public $message;
    public $data;
    public $errorCode;

    public function __construct($message =null, $errorCode = ApiCode::INTERNAL_SERVER_ERROR,Throwable $previous = null,$data=null)
    {
        parent::__construct($message, $errorCode, $previous);
        $this->message=$message ?? __('messages.error.server_error');
        $this->data=$data;
        $this->errorCode=$errorCode;
    }
//public static function erorr (): self{
//    return new self('ddd',400);
//}
//public function render (){
//    return $this->error($this->getMessage(),$this->getCode());
//}


}
