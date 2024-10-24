<?php

namespace src\Interfaces;

interface RequestInterface{
    public function getBody():array;
    public function getAuthCode(): false|string;
    public function getAuthCodeData(): array;
}