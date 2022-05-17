<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;

class MessageController extends AppController
{
    public function actionGet(): string
    {
        $this->log($this->app->checkpoint->model->asArray());
        $this->commutator->send('notification', 'POST', 'pbx/handle', $this->app->instance->data->getData());
        return '<Response/>';
    }

    public function actionSend(): string
{
        $data = $this->app->data();
        $message = $this->pbx->twilioClient->createMessageResource($this->app->instance->event->request->To, $data->config);

        $message['success'] = true;
        $this->log($this->app->checkpoint->model->asArray(), ['event_sid' => $message['sid']]);
        $this->logger->setEventSid($message['sid']);
        $this->logger->addInFile('data', $message);
        return Json::encode($message);
    }

    public function actionStatus(): string
    {
        $this->log($this->app->checkpoint->model->asArray());
        return '<Response/>';
    }
}