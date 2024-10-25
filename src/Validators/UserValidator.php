<?php

namespace src\Validators;

use src\Exceptions\HttpException;
use src\Validators\Validator;

class UserValidator extends Validator{
    public function fieldsValidator(string $fieldName, mixed $fieldContent){
        switch($fieldName){
            case "name":
                $this->nameValidator($fieldContent);
                break;
            case "email":
                $this->emailValidator($fieldContent);
                break;
            case "role":
                $this->roleValidator($fieldContent);
                break;
            case "password":
                $this->passwordValidator($fieldContent);
                break;
            default:
                throw new HttpException(500, "Sorry, there was an internal error");
        }
    }

    private function nameValidator($name){
        if(!is_string($name)){
            throw new HttpException(400, "Name must be of type string");
        }

        return true;
    }

    private function emailValidator($email){
        if(!is_string($email)){
            throw new HttpException(400, "Email must be of type string");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new HttpException(400, "This email is not valid");
        }

        return true;
    }   

    private function roleValidator($role){
        if(!is_string($role)){
            throw new HttpException(400, "Role must be of type string");
        }

        if(!in_array($role, ROLES)){
            throw new HttpException(400, "This role doesn't exist");
        }

        return true;
    }

    private function passwordValidator($passowrd){
        if(!is_string($passowrd)){
            throw new HttpException(400, "Password must be of type string");
        }

        return true;
    }
}