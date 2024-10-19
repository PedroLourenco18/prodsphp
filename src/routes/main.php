<?php

use Pecee\SimpleRouter\SimpleRouter;

try{
    SimpleRouter::setDefaultNamespace("src\Controllers");   

    //ERROR 404 ROUTE
    SimpleRouter::get("prodsphp/404", "NotFoundController@error404");    

    //PRODUCTS ROUTES
    SimpleRouter::get("prodsphp/products", "ProductsController@list");
    SimpleRouter::get("prodsphp/products/{id}", "ProductsController@search");
    SimpleRouter::post("prodsphp/products", "ProductsController@create");
    SimpleRouter::put("prodsphp/products/{id}", "ProductsController@update");
    SimpleRouter::delete("prodsphp/products/{id}", "ProductsController@delete");

    //USER ROUTES
    SimpleRouter::get("prodsphp/user", "UserController@search");
    SimpleRouter::post("prodsphp/user", "UserController@signUp");
    SimpleRouter::put("prodsphp/user", "UserController@update");
    SimpleRouter::delete("prodsphp/user", "UserController@delete");

    //AUTHENTICATION ROUTE
    SimpleRouter::post("prodsphp/user/login", "UserController@login");

    //OAUTH 2.0 ROUTES
    SimpleRouter::get("prodsphp/oauth/google", "OauthController@google");

    SimpleRouter::start();
}catch(Pecee\SimpleRouter\Exceptions\NotFoundHttpException $e){
    header("Location: http://localhost/prodsphp/404");
}