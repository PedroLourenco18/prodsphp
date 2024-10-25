<?php

namespace src\Validators;

use src\Exceptions\HttpException;
use src\Validators\Validator;

class ProductsValidator extends Validator{
    public function fieldsValidator(string $fieldName, mixed $fieldContent){
        switch($fieldName){
            case "brand":
                $this->brandValidator($fieldContent);
                break;
            case "name":
                $this->nameValidator($fieldContent);
                break;
            case "price":
                $this->priceValidator($fieldContent);
                break;
            default:
                throw new HttpException(500, "Sorry, there was an internal error");
        }
    }

    private function brandValidator($brand){
        if(!is_string($brand)){
            throw new HttpException(400, "Brand must be of type string");
        }

        return true;
    }

    private function nameValidator($name){
        if(!is_string($name)){
            throw new HttpException(400, "Name must be of type string");
        }

        return true;
    }   

    private function priceValidator($price){
        if(!(is_int($price) || is_float($price))){
            throw new HttpException(400, "Price must be of type int or float");
        }

        return true;
    }

}