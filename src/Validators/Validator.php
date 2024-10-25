<?php

namespace src\Validators;

use src\Exceptions\HttpException;
use src\Interfaces\ValidatorInterface;

abstract class Validator implements ValidatorInterface{
    public function dataFilter(array $data, bool $allRequired = false){
        $filteredData = [];

        foreach($data as $field => $content){
            if(!$content){
                if($allRequired){
                    throw new HttpException(400, "The field {$field} is required");
                }
                break;
            }else{
                $this->fieldsValidator($field, $content);
                $filteredData[$field] = $content;
                break;
            }
        }

        return $filteredData;
    }

    abstract public function fieldsValidator(string $fieldName, mixed $fieldContent);
}