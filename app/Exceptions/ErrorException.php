<?php

namespace App\Exceptions;

use App\ApiHelper\ApiCode;
use Exception;
use Throwable;

class ErrorException extends Exception
{
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


}
