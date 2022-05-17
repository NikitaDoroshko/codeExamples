<?php


namespace app\modules\api\modules\integration\controllers;


use app\models\Config;
use app\modules\api\modules\integration\models\CallbackRequest;
use app\modules\api\modules\integration\models\InstallRequest;
use app\modules\api\controllers\AmoController;
use app\modules\api\modules\integration\models\UninstallRequest;
use app\src\integration\components\Account;
use app\src\integration\components\AuthToken;
use app\src\integration\components\Chat;
use app\src\integration\exceptions\InstallException;
use crmpbx\logger\Logger;


class ServiceController extends AmoController
{
    private Logger $log;

    public function __construct($id, $module, $config = [])
    {
        $this->log = \Yii::$app->logger;
        parent::__construct($id, $module, $config);
    }

    public function actionInstall(): array
    {
        $request = new InstallRequest(\Yii::$app->request->bodyParams);
        $config = Config::findOne(['url' => 'https://'.$request->referer]);

        if (!($config instanceof Config))
            $config = Config::new($request->referer);

        $this->log->init(\Yii::$app->request->url, $config->company_sid);

        $token = new AuthToken(
            AUTH_VAULT_SERVICE_ADDRESS,
            AUTH_VAULT_SERVICE_ACCESS_TOKEN,
            $config->company_sid,
            $config->sid,
            $config->url
        );

        $m_token = $token->install($request);

        try {
            $account = (new Account($config->url, $m_token->asString()))->get();
            $chat = (new Chat($config->url, $account->amojo_id))->connect();
        }catch (\Throwable $exception){
            Throw new InstallException('Install error.');
        }

        $configData = [
            'amojo_id' => $account->amojo_id,
            'account_id' => (string)$account->id,
            'scope_id' => $chat->scope_id,
        ];

        $account_m = $config->account ?? new \app\models\Account(['config_id'=>$config->id]);
        if ($account_m->load($configData, '') && $account_m->save())
            return [
                'success' => true,
                'config' => $config->attributes
            ];

        return ['success' => false];
    }


    public function actionUninstall(): array
    {
        $request = new UninstallRequest(\Yii::$app->request->bodyParams);
        if($account = \app\models\Account::findOne(['account_id' => $request->account_id])){
            $config = $account->config;
            $token = new AuthToken(
                AUTH_VAULT_SERVICE_ADDRESS,
                AUTH_VAULT_SERVICE_ACCESS_TOKEN,
                $config->company_sid,
                $config->sid,
                $config->url
            );

            $success = [];
            $success['token'] = $token->delete();
            if($success['token'])
                $success['account'] = (bool)$account->delete();

            return [
                'success' => $success,
                'config' => $config->attributes
            ];
        }

        if(isset($config))
            $this->log->init(\Yii::$app->request->url, $config->company_sid);

        return ['success' => false];
    }


    public function actionCallback()
    {
        $request = new CallbackRequest(\Yii::$app->request->bodyParams);
    }
}