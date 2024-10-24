<?php

namespace src\Authorizers;

use ArrayAccess;
use src\Exceptions\HttpException;

class Authorizer{
    public function authorize(string $userRole, string $roleRequired){
        if(array_search($userRole, ROLES) >= array_search($roleRequired, ROLES)){
            return true;
        }

        throw new HttpException(403, "You don't have permission to access this method");
    }
}