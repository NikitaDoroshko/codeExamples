<?php


namespace app\models;


use yii\helpers\Json;
use yii\web\BadRequestHttpException;

class Model extends \yii\base\Model
{
    public function __construct($config = [])
    {
        parent::__construct($this->config($config));
    }

    protected function config($data): array
    {
        $config = [];
        foreach ($data as $field => $value)
            if(property_exists(static::class, $field))
                $config[$field] = $value;

        return $config;
    }

    public function asArray(): array
    {
        $data = [];
        foreach (static::fields() as $field)
            if (isset($this->$field))
                $data[$field] = $this->$field;

        return $data;
    }

    public static function resolve(array $config): static
    {
        $model = new static($config);
        if(!$model->validate())
            Throw new BadRequestHttpException(Json::encode($model->getErrors()), 400);

        return $model;
    }
}