<?php

namespace src\Services;

use src\Exceptions\HttpException;
use src\Services\Service;
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv =  Dotenv::createImmutable(DEVELOPMENT_URL);
$dotenv->load();

class UserService extends Service{
    public function login(array $data){
        $filteredData = $this->validator->dataFilter([
            "email" => $data["email"] ?? null,
            "password" => $data["password"] ?? null
        ], true);

        $user = $this->model->searchByEmail($filteredData["email"]);

        if(!($user && password_verify($filteredData["password"], $user["password"]))){
            throw new HttpException(400, "Invalid email or password");
        }

        $payload = [
            "sub" => $user["id"],
            "name" => $user["name"],
            "role" => $user["role"]
        ];

        $jwt = JWT::encode($payload, $_ENV["JWT_KEY"], 'HS256');

        return $jwt;
    }
    public function searchById(int $id){
        $user = $this->model->searchById($id);
        if(!$user){
            throw new HttpException(400, "User not found");
        }

        return $user;
    }

    public function searchByEmail(string $email){
        $user = $this->model->searchByEmail($email);
        if(!$user){
            throw new HttpException(400, "User not found");
        }

        return $user;
    }

    public function create(array $data){
        $filteredData = $this->validator->dataFilter([
            "name" => $data["name"] ?? null,
            "email" => $data["email"] ?? null,
            "role" => $data["role"] ?? null,
            "password" => $data["password"] ?? null
        ], true);

        if($this->model->searchByEmail($filteredData["email"])){
            throw new HttpException(400, "This email is already being used by another account");
        }

        $filteredData["password"] = password_hash($filteredData["password"], PASSWORD_DEFAULT);

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

    public function update(int $id, array $data){
        if(!$this->model->searchById($id)){
            throw new HttpException(400, "User not found");
        }

        $filteredData = $this->validator->dataFilter([
            "name" => $data["name"] ?? null,
            "email" => $data["email"] ?? null,
            "role" => $data["role"] ?? null,
            "password" => $data["password"] ?? null
        ], false);

        if(isset($filteredData["email"]) && $this->model->searchByEmail($filteredData["email"])){
            throw new HttpException(400, "This email is already being used by another account");
        }

        if(isset($filteredData["password"])){
            $filteredData["password"] = password_hash($filteredData["password"], PASSWORD_DEFAULT);
        }
            
        $this->model->update($id,$filteredData);
    }

    public function delete(int $id){
        if(!$this->model->searchById($id)){
            throw new HttpException(400, "User not found");
        }

        $this->model->delete($id);
    }
}