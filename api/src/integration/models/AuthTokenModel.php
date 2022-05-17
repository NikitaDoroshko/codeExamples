<?php


namespace app\src\integration\models;

use app\models\Model;
use app\src\integration\exceptions\TokenException;

class AuthTokenModel extends Model
{
    public string $type;
    public string $access_token;

    public function asString(): string
    {
        try {
            return $this->type.' '.$this->access_token;
        }catch (\Error $e){
            Throw new TokenException('Token was not found');
        }

    }
}