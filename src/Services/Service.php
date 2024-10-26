<?php

namespace src\Services;

use src\Interfaces\ModelInterface;
use src\Interfaces\ValidatorInterface;

abstract class Service{
    protected ModelInterface $model;
    protected ValidatorInterface $validator;

    public function __construct(ModelInterface $model, ValidatorInterface $validator){
        $this->model = $model;
        $this->validator = $validator;
    }
}