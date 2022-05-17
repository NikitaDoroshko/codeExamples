<?php


namespace app\modules\api\modules\integration\controllers;


use app\modules\api\modules\integration\models\User;
use app\modules\api\modules\integration\models\UserSearch;
use yii\base\BaseObject;
use yii\db\ActiveRecord;

class UserController extends BaseController
{
    public $modelClass = 'app\modules\api\modules\integration\models\User';

    public function actions(): array
    {
        $actions =  parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new UserSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}