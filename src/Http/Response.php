<?php

namespace src\Http;

class Response{
    private int $httpCode;
    private bool $error;
    private string $message;
    private string $authToken;
    private array $data;
    public function setHttpCode(int $httpCode){
        $this->httpCode = $httpCode;
    }

    public function setError(bool $error){
        $this->error = $error;
    }

    public function setMessage(string $message){
        $this->message = $message;
    }

    public function setAuthToken(string $authToken){
        $this->authToken = $authToken;
    }

    public function setData(array $data){
        $this->data = $data;
    }

    public function send(){
        if(!isset($this->httpCode) || !isset($this->error)){
            return false;
        }

        $body = [];
        $body["error"] = $this->error;

        if(isset($this->message)){
            $body["message"] = $this->message;
        }

        if(isset($this->authToken)){
            $body["auth_token"] = $this->authToken;
        }

        if(isset($this->data)){
            $body["data"] = $this->data;
        }

        http_response_code($this->httpCode);
        header("Access-Control-Allow-Origin: *");
        
        header("Content-Type: application/json"); 
        
        echo json_encode($body); 
    }
}