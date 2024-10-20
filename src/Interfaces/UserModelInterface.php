<?php

namespace src\Interfaces;

use src\Interfaces\DbConnectionInterface;

interface UserModelInterface{
    public function __construct(DbConnectionInterface $dbConnection);
    public function searchById(int $id);
    public function searchByEmail(string $email);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}