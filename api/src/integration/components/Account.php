<?php

namespace app\src\integration\components;

use app\src\integration\models\AccountModel;
use app\src\integration\request\AmoRequest;
use crmpbx\httpClient\Response;


class Account extends AmoComponent
{
    protected string $modelClass = 'app\src\integration\models\AccountModel';

    public function get()
    {
        $response = $this->getResponse('GET', '/api/v4/account?with=amojo_id');
        return $this->getModel($response);
    }
}