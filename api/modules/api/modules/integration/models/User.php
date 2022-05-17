<?php


namespace app\modules\api\modules\integration\models;


use yii\helpers\Url;
use yii\web\Linkable;

class User extends \app\models\User implements Linkable
{
    public function extraFields(): array
    {
        return [
            'config' => 'config'
        ];
    }

    public function getLinks()
    {
        return [
            'self' => Url::to(['user/view', 'id' => $this->id], true),
            'update' => Url::to(['user/update', 'id' => $this->id], true)
        ];
    }

}