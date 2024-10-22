<?php

namespace src\Models;

use PDO;
use src\Interfaces\DbConnectionInterface;
use Dotenv\Dotenv;

$dotenv =  Dotenv::createImmutable(__DIR__);
$dotenv->load();

class MySQLConnection implements DbConnectionInterface{
    private PDO $connection;
    public function __construct(){
        $this->connection = new PDO("mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}", $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"]);
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