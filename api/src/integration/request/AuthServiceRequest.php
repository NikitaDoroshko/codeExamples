<?php


namespace app\src\integration\request;


class AuthServiceRequest extends BaseRequest
{
    public function __construct($authToken, $method, $uri, $body)
    {
        parent::__construct($authToken, $method, $uri, $body, $this->setHeaders([
            'Authorization'=> 'Bearer '. $authToken
        ]));
    }


}