<?php


namespace app\src\integration\request;


class AmoRequest extends BaseRequest
{

    public function __construct(string $authToken, $method, $uri, $body = null)
    {
        parent::__construct($authToken, $method, $uri, $body, $this->setHeaders([
            'Authorization' => $authToken
        ]));
    }
}