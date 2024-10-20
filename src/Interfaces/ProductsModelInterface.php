<?php

namespace src\Interfaces;

use src\Interfaces\DbConnectionInterface;

interface ProductsModelInterface{
    public function __construct(DbConnectionInterface $dbConnection);
    public function read();
    public function searchById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}