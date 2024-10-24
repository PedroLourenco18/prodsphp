<?php

namespace src\Http;

use src\Interfaces\RequestInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use src\Exceptions\HttpException;

$dotenv =  Dotenv::createImmutable(__DIR__);
$dotenv->load();

class JWTRequest implements RequestInterface{
    private array $body;
    private string $jwt;
    private array $jwtPayload;
    public function getBody():array{
        if(!isset($this->body)){   
            $method = $_SERVER['REQUEST_METHOD'];
            
            $this->body = match($method){
                "GET" => $_GET,
                default => json_decode(file_get_contents('php://input', true), true)
            };
        }

        return $this->body;
    }

    public function getAuthCode(): false|string{
        if(!isset($this->jwt)){
            $headers = getallheaders();
            if(isset($headers['Authorization'])) {
                $this->jwt = str_replace('Bearer ', '', $headers['Authorization']);
            }else{
                return false;
            }
        }

        return $this->jwt;
    }

    public function getAuthCodeData(): array{
        if(!isset($this->jwtPayload)){
            try{
                $decoded = JWT::decode($this->getAuthCode(), new Key($_ENV["JWT_KEY"], 'HS256'));
                
                //trasnform std class to array
                $this->jwtPayload = json_decode(json_encode($decoded), true);
            }catch(\Exception $exception){
                throw new HttpException(400, "The authorization token is invalid or was not given");
            }
        }

        return $this->jwtPayload;
    }
}