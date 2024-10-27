<?php

namespace src\Controllers;

use src\Interfaces\RequestInterface;
use src\Interfaces\DbConnectionInterface;
use src\Interfaces\UserModelInterface;

use src\Exceptions\HttpException;
use src\Http\JWTRequest;
use src\Http\Response;
use src\Models\MySQLConnection;
use src\Models\UserModel;
use src\Services\UserService;
use src\Authorizers\Authorizer;
use src\Validators\UserValidator;

class UserController{
    private RequestInterface $request;
    private Response $response;
    private DbConnectionInterface $connection;
    private UserModelInterface $model;
    private UserService $service;
    private Authorizer $authorizer;
    private UserValidator $validator;

    public function __construct(){
        try{
            $this->request = new JWTRequest();
            $this->response = new Response();
            $this->connection = new MySQLConnection();
            $this->model = new UserModel($this->connection);
            $this->validator = new UserValidator();
            $this->service = new UserService($this->model, $this->validator);
            $this->authorizer = new Authorizer();
        }catch(\PDOException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode(500);
            $this->response->setMessage("Sorry, there was an internal error: {$exception->getMessage()}");
            $this->response->send();
        }
    }

    public function login(){
        try{
            $loginData = $this->request->getBody();

            $jwt = $this->service->login($loginData);

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setMessage($jwt);
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function search(){
        try{
            $userId = $this->request->getAuthCodeData()["sub"];

            $userData = $this->service->searchById($userId);

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setData($userData);
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function signUp(){
        try{
            $newUserData = $this->request->getBody();

            $jwt= $this->service->create($newUserData);

            $this->response->setError(false);
            $this->response->setHttpCode(201);
            $this->response->setMessage($jwt);
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function update(){
        try{
            $userData = $this->request->getAuthCodeData();

            $newUserData = $this->request->getBody();

            $this->service->update($userData["sub"], $newUserData);

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setMessage("Information has been updated successfully");
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function delete(){
        try{
            $userData = $this->request->getAuthCodeData();

            $this->service->delete($userData["sub"]);

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setMessage("User '{$userData["name"]}' deleted");
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }
}