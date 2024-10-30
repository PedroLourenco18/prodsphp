<?php

namespace src\Controllers;

use src\Controllers\OauthController;

use src\Services\GoogleOauthService;

class GoogleOauthController extends OauthController{
    public function __construct(){
        parent::__construct();

        $this->service = new GoogleOauthService($this->model, $this->validator);
    }
}