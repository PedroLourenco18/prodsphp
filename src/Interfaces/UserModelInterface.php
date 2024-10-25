<?php

namespace src\Interfaces;

use src\Interfaces\ModelInterface;

interface UserModelInterface extends ModelInterface{
    public function searchById(int $id);
    public function searchByEmail(string $email);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}