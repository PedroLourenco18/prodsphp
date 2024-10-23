<?php

namespace src\Interfaces;

interface DbConnectionInterface{
    public function fetch(string $sql, array $params = null);
    public function fetchAll(string $sql, array $params = null);
    public function execute(string $sql, array $params = null);
    public function getLastInsertId();
    public function getRowCount();
}