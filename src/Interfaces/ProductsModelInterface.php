<?php

namespace src\Interfaces;

use src\Interfaces\ModelInterface;

interface ProductsModelInterface extends ModelInterface{
    public function read();
    public function searchById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}