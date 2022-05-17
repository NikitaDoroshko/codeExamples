<?php


namespace app\modules\api\modules\integration\models;


use app\models\Model;

class InstallRequest extends Model
{
    public string $code;
    public string $referer;
}
