<?php


namespace app\modules\api\modules\integration\models;


use yii\helpers\Url;
use yii\web\Linkable;

class Config extends \app\models\Config implements Linkable
{

    public function extraFields(): array
    {
        return [
            'users' => 'users'
        ];
    }

    public function getLinks()
    {
        return [
            'self' => Url::to(['config/view', 'id' => $this->id], true),
            'update' => Url::to(['config/update', 'id' => $this->id], true)
        ];
    }
}