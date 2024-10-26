<?php

namespace src\Services;

use src\Exceptions\HttpException;
use src\Services\Service;

class ProductsService extends Service{
    public function list(){
        $products = $this->model->read();
        if(!$products){
            throw new HttpException(400, "No products found");
        }

        return $products;
    }

    public function search(int $id){
        $product = $this->model->searchById($id);
        if(!$product){
            throw new HttpException(400, "Product not found");
        }

        return $product;
    }

    public function create(array $data){
        $filteredData = $this->validator->dataFilter([
            "brand" => $data["brand"] ?? null,
            "name" => $data["name"] ?? null,
            "price" => $data["price"] ?? null
        ], true);

        $productId = $this->model->create($filteredData);

        if(!$productId){
            throw new HttpException(500, "Sorry, there was an internal error");
        }

        return $productId;
    }

    public function update(int $id, array $data){
        if(!$this->model->searchById($id)){
            throw new HttpException(400, "Product not found");
        }

        $filteredData = $this->validator->dataFilter([
            "brand" => $data["brand"] ?? null,
            "name" => $data["name"] ?? null,
            "price" => $data["price"] ?? null
        ],false);

        $this->model->update($id,$filteredData);
    }

    public function delete(int $id){
        if(!$this->model->searchById($id)){
            throw new HttpException(400, "Product not found");
        }

        $this->model->delete($id);
    }
}