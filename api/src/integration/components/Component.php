<?php

namespace app\src\integration\components;

use app\src\integration\exceptions\ComponentException;
use crmpbx\httpClient\HttpClient;
use crmpbx\httpClient\Response;


abstract class Component
{
    protected string $domain;
    protected string $authToken;

    protected string $modelClass;

    protected HttpClient $httpClient;

    public function __construct($domain, $authToken)
    {
        $this->httpClient = \Yii::$app->httpClient;
        $this->domain = $domain;
        $this->authToken = $authToken;
    }

    protected function getModel(Response $response)
    {
        return new $this->modelClass($response->body);
    }

    abstract protected function setRequest(string $method, string $route, array $body);

    protected function getResponse(string $method, $route, array $body = null, array $allowedStatusList = [200]):Response
    {
        $response = $this->httpClient->getResponse($this->setRequest($method, $route, $body));
        if($response && in_array($response->status, $allowedStatusList))
            return $response;

        Throw new ComponentException(sprintf('Response failed: %s %s', $response->status, $response->reason));
    }
}