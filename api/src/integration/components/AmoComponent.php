<?php

namespace app\src\integration\components;

use app\src\integration\request\AmoRequest;


abstract class AmoComponent extends Component
{
    protected function setRequest(string $method, string $route, array $body): AmoRequest
    {
        return new AmoRequest($this->authToken, $method, $this->domain . $route, $body);
    }
}