<?php

namespace src\Models;

use src\Interfaces\UserModelInterface;
use src\Interfaces\DbConnectionInterface;

class UserModel implements UserModelInterface{
    private $con;
    public function __construct(DbConnectionInterface $dbConnection){
        $this->con = $dbConnection;
    }

    public function searchById(int $id){
        $sql = "SELECT id, name, email, role FROM users WHERE id = {$id}";

        return $this->con->fetch($sql);
    }

    public function searchByEmail(string $email){
        $sql = "SELECT * FROM users WHERE email = '{$email}'";

        return $this->con->fetch($sql);
    }

    public function create(array $data){
        $sql = "INSERT INTO users (`id`, `name`, `email`, `role`, `password`) VALUES (NULL, ?, ?, ?, ?);";

        $this->con->execute($sql, [
            $data["name"],
            $data["email"],
            $data["role"],
            $data["password"]
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
        
        $sql = "UPDATE users SET {$columns} WHERE id = {$id}";

        $this->con->execute($sql, $values);
    }

    public function delete(int $id){
        $sql = "DELETE FROM users WHERE id = {$id}";

        $this->con->execute($sql);
    }
}