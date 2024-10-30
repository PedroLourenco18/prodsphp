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
use src\Services\OauthService;
use src\Validators\UserValidator;

abstract class OauthController{
    protected RequestInterface $request;
    protected Response $response;
    protected DbConnectionInterface $connection;
    protected UserModelInterface $model;
    protected OauthService $service;
    protected UserValidator $validator;

    public function __construct(){
        try{
            $this->request = new JWTRequest();
            $this->response = new Response();
            $this->connection = new MySQLConnection();
            $this->model = new UserModel($this->connection);
            $this->validator = new UserValidator();
        }catch(\PDOException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode(500);
            $this->response->setMessage("Sorry, there was an internal error: {$exception->getMessage()}");
            $this->response->send();
        }
    }

    public function auth(){
        try{
            if(!isset(($this->request->getBody()["code"]))){
                throw new HttpException(400, "Auth Code is missing");
            }

            $authCode = $this->request->getBody()["code"];

            $jwt = $this->service->auth($authCode);

            $this->response->setError(false);
            $this->response->setHttpCode($this->service->accountExists? 200 : 201);
            $this->response->setAuthToken($jwt);
        }catch(\Exception $exception){
            $this->response->setError(true);
            $this->response->setHttpCode(500);
            $this->response->setMessage("Sorry, there was an internal error: {$exception->getMessage()}");
        }finally{
            $this->response->send();
        }
    }

    public function getLink(){
        try{
            echo $this->service->getLink();
        }catch(\Exception $exception){
            $this->response->setError(true);
            $this->response->setHttpCode(500);
            $this->response->setMessage("Sorry, there was an internal error: {$exception->getMessage()}");
            $this->response->send();
        }
    }
}