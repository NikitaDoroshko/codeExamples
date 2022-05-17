<?php


namespace app\controllers;

use app\src\Pbx;
use crmpbx\commutator\Commutator;
use crmpbx\logger\Logger;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;


abstract class AppController extends \yii\rest\Controller
{
    protected Pbx $pbx;
    protected \app\src\pbx\Pbx $app;
    protected Logger $logger;
    protected Commutator $commutator;

    public function behaviors(): array
    {
        $this->build();

        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['*/*'] = Response::FORMAT_RAW;
        return $behaviors;
    }

    private function build(): void
    {
        $this->commutator = Yii::$app->commutator;
        $this->logger = Yii::$app->logger;

        $this->pbx = new Pbx(
            Yii::$app->request->bodyParams,
            Yii::$app->controller->id,
            Yii::$app->controller->action->id,
            Yii::$app->twilio,
            Yii::$app->logger
        );

        $this->app = $this->pbx->build();

        if ($this->app->instance->isInBlackList())
            throw new ForbiddenHttpException('Client is in blacklist');
    }

    protected function log($checkpoint = array(), $params = array())
    {
        if ('call' === $this->app->instance->event->event)
            $params['result'] = $this->app->instance->event->status->getCallResult();
        if (!in_array('hangup', $checkpoint, true))
            $params['checkpoint'] = $checkpoint;

        $this->app->instance->log->save($params);
    }
}