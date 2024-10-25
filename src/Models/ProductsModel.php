<?php

namespace src\Models;

use src\Interfaces\ProductsModelInterface;
use src\Interfaces\DbConnectionInterface;

class ProductsModel implements ProductsModelInterface{
    private $con;
    public function __construct(DbConnectionInterface $dbConnection){
        $this->con = $dbConnection;
    } 

    public function read(){
        $sql = "SELECT * FROM products";

        return $this->con->fetchAll($sql);
    }

    public function searchById(int $id){
        $sql = "SELECT * FROM products WHERE id = {$id}";

        return $this->con->fetch($sql);
    }

    public function create(array $data){
        $sql = "INSERT INTO products (`id`, `brand`, `name`, `price`) VALUES (NULL, ?, ?, ?);";

        $this->con->execute($sql, [
            $data["brand"],
            $data["name"],
            $data["price"]
        ]);

        return $this->con->getLastInsertId();
    }

    public function update(int $id, array $data){
        $columns = "";
        $values = [];

        foreach($data as $key => $value){
            $columns .= $key." =?, ";
            array_push($values, $value);
        }
        $columns = substr($columns, 0, -2);
        
        $sql = "UPDATE products SET {$columns} WHERE id = {$id}";

        $this->con->execute($sql, $values);
    }

    public function delete(int $id){
        $sql = "DELETE FROM products WHERE id = {$id}";

        $this->con->execute($sql);
    }
}