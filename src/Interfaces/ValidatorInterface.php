<?php

namespace src\Interfaces;

interface ValidatorInterface{
    public function dataFilter(array $data, bool $allRequired);

    public function fieldsValidator(string $fieldName, mixed $fieldContent);
}