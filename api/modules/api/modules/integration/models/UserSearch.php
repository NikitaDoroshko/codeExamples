<?php


namespace app\modules\api\modules\integration\models;


use yii\base\BaseObject;
use yii\data\ActiveDataProvider;

/**
 *
 * @property string $url
 */
class UserSearch extends User
{
    public $url = null;

    public function rules() {
        return [
            [['amo_sid'], 'integer'],
            [['sid', "url"], 'safe'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = User::find()->innerJoinWith('config', false);
        $this->load($params, '');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
//            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'sid', $this->sid]);
        $query->andFilterWhere(['like', 'amo_sid', $this->amo_sid]);
        $query->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}

