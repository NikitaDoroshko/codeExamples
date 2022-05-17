<?php


namespace app\modules\api\modules\integration\models;


use yii\data\ActiveDataProvider;

/**
 * @property string $url
 * @property string $configSid
 */
class UserSearch extends User
{
    public string $url;
    public string $configSid;

    public function rules() {
        return [
            [['amo_sid'], 'integer'],
            [['sid', "url", "configSid", "companySid"], 'safe'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $this->load($params, '');

        $query = User::find();
        $query->andFilterWhere(['like', 'sid', $this->sid]);
        $query->andFilterWhere(['like', 'amo_sid', $this->amo_sid]);

        if (($this->configSid ?? false) || ($this->url ?? false)){
            $query->joinWith('config');
            $query->andFilterWhere(['like', 'config.sid', $this->configSid]);
            $query->andFilterWhere(['like', 'company_sid', $this->configSid]);
            $query->andFilterWhere(['like', 'url', $this->url]);
        }

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


        return $dataProvider;
    }
}
