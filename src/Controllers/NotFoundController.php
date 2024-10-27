<?php

namespace src\Controllers;

use src\Http\Response;

class NotFoundController {
    public function error404(){
        $response = new Response();

        $response->setError(true);
        $response->setHttpCode(404);
        $response->setMessage("Route not Found");
        $response->send();
    }
}