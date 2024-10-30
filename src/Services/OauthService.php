<?php

namespace src\Services;

use src\Exceptions\HttpException;
use src\Services\Service;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;
use src\Interfaces\ModelInterface;
use src\Interfaces\ValidatorInterface;

$dotenv =  Dotenv::createImmutable(DEVELOPMENT_URL);
$dotenv->load();

abstract class OauthService extends Service{
    protected string $authProvider;
    public bool $accountExists;

    public function __construct(ModelInterface $model, ValidatorInterface $validator, string $authProvider){
        parent::__construct($model, $validator);

        $this->authProvider = $authProvider;
    }

    abstract public function getLink();

    abstract public function auth(string $authCode);

    abstract public function getUserInfo():array;

    public function create(array $data){
        $filteredData = $this->validator->dataFilter([
            "name" => $data["name"] ?? null,
            "email" => $data["email"] ?? null
        ], true);
        $filteredData["role"] = "junior";
        $filteredData["auth_provider"] = $this->authProvider;
        $filteredData["password"] = "";

        $userId = $this->model->create($filteredData);

        if(!$userId){
            throw new HttpException(500, "Sorry, there was an internal error");
        }

        $payload = [
            "sub" => $userId,
            "name" => $filteredData["name"],
            "role" => $filteredData["role"]
        ];

        $jwt = JWT::encode($payload, $_ENV["JWT_KEY"], 'HS256');

        return $jwt;
    }

    public function login(array $user){
        if($user["auth_provider"] != $this->authProvider){
            throw new HttpException(400, "This account is registered with {$user["auth_provider"]}. Try signing in with {$user["auth_provider"]} or another account");
        }

        $payload = [
            "sub" => $user["id"],
            "name" => $user["name"],
            "role" => $user["role"]
        ];

        $jwt = JWT::encode($payload, $_ENV["JWT_KEY"], 'HS256');

        return $jwt;
    }
}