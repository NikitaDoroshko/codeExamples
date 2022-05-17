<?php

namespace app\models;

use Yii;
use yii\base\BaseObject;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $sid
 * @property string $company_name
 * @property string $account_sid
 * @property string $company_sid
 * @property string $url
 * @property array $config
 * @property array $settings
 *
 * @property Account $account
 * @property Log[] $logs
 * @property User[] $users
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['config', 'settings'], 'safe'],
            [['company_name', 'account_sid', 'url', 'sid'], 'string', 'max' => 255],
            [['company_sid'], 'required'],
            [['sid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'company_name' => 'Company Name',
            'account_sid' => 'Account Sid',
            'company_sid' => 'Company Sid',
            'url' => 'Url',
            'config' => 'Settings',
        ];
    }

    public function afterFind()
    {
        $this->url = 'https://'.str_replace(['https://', '/'], '', $this->url);
//        $this->config = new ConfigSettings($this->config);
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public static function new($url): Config
    {
        $config = new self([
            'sid' => 'INT'.md5(time().rand(0,999)),
            'company_name' => explode('.amocrm.', str_replace(['http://', 'https://'], '',$url))[0],
            'company_sid' => 'CO'.md5(time().rand(0,999)),
            'url' => sprintf('https://%s', str_replace(['http://', 'https://'], '',$url)),
            'settings' => Yii::$app->params['basicConfig']['settings'],
            'config' => Yii::$app->params['basicConfig']['config']
        ]);

        if($config->save())
            return $config;
    }

    /**
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Log::class, ['config_id' => 'id']);
    }

    /**
     * Gets query for [[Account]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccount(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Account::class,  ['config_id' => 'id']);
    }
    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers(): \yii\db\ActiveQuery
    {
        return $this->hasMany(User::class, ['config_id' => 'id']);
    }
}