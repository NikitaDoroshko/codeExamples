<?php

namespace app\modules\api\modules\integration\controllers;

use app\modules\api\modules\integration\models\ConfigSearch;


class ConfigController extends BaseController
{
    public $modelClass = 'app\modules\api\modules\integration\models\Config';

    public function actions(): array
    {
        $actions =  parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider(): \yii\data\ActiveDataProvider
    {
        $searchModel = new ConfigSearch();
        return $searchModel->search(\Yii::$app->request->bodyParams);
    }
}