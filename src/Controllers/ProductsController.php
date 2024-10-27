<?php

namespace src\Controllers;

use src\Interfaces\RequestInterface;
use src\Interfaces\DbConnectionInterface;
use src\Interfaces\ProductsModelInterface;

use src\Exceptions\HttpException;
use src\Http\JWTRequest;
use src\Http\Response;
use src\Models\MySQLConnection;
use src\Models\ProductsModel;
use src\Services\ProductsService;
use src\Authorizers\Authorizer;
use src\Validators\ProductsValidator;

class ProductsController{
    private RequestInterface $request;
    private Response $response;
    private DbConnectionInterface $connection;
    private ProductsModelInterface $model;
    private ProductsService $service;
    private Authorizer $authorizer;
    private ProductsValidator $validator;
    public function __construct(){
        try{
            $this->request = new JWTRequest();
            $this->response = new Response();
            $this->connection = new MySQLConnection();
            $this->model = new ProductsModel($this->connection);
            $this->validator = new ProductsValidator();
            $this->service = new ProductsService($this->model, $this->validator);
            $this->authorizer = new Authorizer();
        }catch(\PDOException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode(500);
            $this->response->setMessage("Sorry, there was an internal error: {$exception->getMessage()}");
            $this->response->send();
        }
    }
    public function list(){
        try{
            $data = $this->service->list();

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setData($data);
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function search($id){
        try{
            $data = $this->service->search($id);

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setData($data);
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function create(){
        try{
            $userData = $this->request->getAuthCodeData();
            $this->authorizer->authorize($userData["role"], "senior");

            $productData = $this->request->getBody();

            $productId = $this->service->create($productData);

            $data = $this->service->search($productId);

            $this->response->setError(false);
            $this->response->setHttpCode(201);
            $this->response->setData($data);
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function update($id){
        try{
            $userData = $this->request->getAuthCodeData();
            $this->authorizer->authorize($userData["role"], "senior");

            $productData = $this->request->getBody();

            $this->service->update($id,$productData);

            $data = $this->service->search($id);

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setData($data);
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }

    public function delete($id){
        $userData = $this->request->getAuthCodeData();
        $this->authorizer->authorize($userData["role"], "senior");

        try{
            $this->service->delete($id);

            $this->response->setError(false);
            $this->response->setHttpCode(200);
            $this->response->setMessage("The product has been deleted successfully");
        }catch(HttpException $exception){
            $this->response->setError(true);
            $this->response->setHttpCode($exception->getHttpCode());
            $this->response->setMessage($exception->getMessage());    
        }finally{
            $this->response->send();
        }
    }
}