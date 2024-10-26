<?php

namespace src\Services;

use src\Exceptions\HttpException;
use src\Services\Service;

class UserService extends Service{
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

        return $userId;
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
        ], true);

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