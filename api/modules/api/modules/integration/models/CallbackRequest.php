<?php

namespace app\modules\api\modules\integration\models;

use app\models\Model;

class CallbackRequest extends Model
{
    public string $To;
    public string|array $From;
    public string $Direction;
    public string $domain;
}
