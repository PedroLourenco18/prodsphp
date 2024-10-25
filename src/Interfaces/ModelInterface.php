<?php

namespace src\Interfaces;

use src\Interfaces\DbConnectionInterface;

interface ModelInterface{
    public function __construct(DbConnectionInterface $dbConnection);
}