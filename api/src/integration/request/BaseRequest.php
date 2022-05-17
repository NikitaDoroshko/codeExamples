<?php


namespace  app\src\integration\request;

use yii\helpers\Json;

abstract class BaseRequest extends \GuzzleHttp\Psr7\Request
{
    private string $authToken;

    public function __construct($authToken, $method, $uri, $body = null, $headers = [])
    {
        $this->authToken = $authToken;
        parent::__construct($method, $uri, $this->setHeaders($headers), $this->setBody($body));
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setHeaders($headers): array
    {
        return array_merge($headers, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'curl'
        ]);
    }

    public function setBody($data): string
    {
        return Json::encode($data);
    }
}