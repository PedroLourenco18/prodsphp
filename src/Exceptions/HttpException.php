<?php

namespace src\Exceptions;

use Exception;

class HttpException extends Exception{
    private int $httpCode;
    public function __construct(int $httpCode = 400, string $message = "", int $code = 0, \Throwable $previous = null){
        $this->httpCode = $httpCode;

        parent::__construct($message,$code,$previous);
    }

    public function getHttpCode(){
        return $this->httpCode;
    }
}