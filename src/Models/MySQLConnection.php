<?php

namespace src\Models;

use PDO;
use src\Interfaces\DbConnectionInterface;

class MySQLConnection implements DbConnectionInterface{
    private PDO $connection;
    public function __construct(){
        $this->connection = new PDO('mysql:host=localhost;dbname=api', "root", "");
    }
    public function fetch(string $sql, array $params = null){
        $stmt = $this->connection->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll(string $sql, array $params = null){
        $stmt = $this->connection->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}