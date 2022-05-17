<?php

namespace app\modules\api\modules\integration\models;

use yii\base\BaseObject;
use yii\data\ActiveDataProvider;

class ConfigSearch extends Config
{
    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = Config::find();
        $query->andFilterWhere(['like', 'company_sid', $this->company_sid]);
        $query->andFilterWhere(['like', 'sid', $this->sid]);
        $query->andFilterWhere(['like', 'url', $this->url]);

        return  new ActiveDataProvider([
            'query' => $query
        ]);
    }
}