<?php

namespace src\Services;

use src\Services\OauthService;

use src\Interfaces\ModelInterface;
use src\Interfaces\ValidatorInterface;
use Google\Service\Oauth2;
use Google\Client;
class GoogleOauthService extends OauthService{
    private Client $client;
    public function __construct(ModelInterface $model, ValidatorInterface $validator){
        parent::__construct($model, $validator, "google");

        $this->client = new Client();
        $this->client->setAuthConfig(__DIR__.'/../../client_credentials.json');
        $this->client->addScope("email");
        $this->client->addScope("profile");
        $this->client->setRedirectUri(DEVELOPMENT_URL."oauth2/google");
    }
    
    public function getLink(){
        return $this->client->createAuthUrl();
    }

    public function auth(string $authCode){
        $token = $this->client->fetchAccessTokenWithAuthCode($authCode);
        $this->client->setAccessToken($token);

        $userInfo = $this->getUserInfo();

        if($user = $this->model->searchByEmail($userInfo["email"])){
            $this->accountExists = true;
            return $this->login($user);
        }else{
            $this->accountExists = false;
            return $this->create($userInfo);
        }
    }
    
    public function getUserInfo(): array{
        $googleService = new Oauth2($this->client);
        $userinfo = $googleService->userinfo->get();
        return [
            "name" => $userinfo["name"],
            "email" => $userinfo["email"]
        ];
    }
}