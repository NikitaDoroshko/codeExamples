<?php

namespace app\src\integration\components;

use app\modules\api\modules\integration\models\InstallRequest;
use app\src\integration\exceptions\ComponentException;
use app\src\integration\models\AuthTokenModel;
use app\src\integration\request\AuthServiceRequest;
use crmpbx\httpClient\Response;


class AuthToken extends Component
{
    protected string $modelClass = 'app\src\integration\models\AuthTokenModel';
    protected AuthTokenModel $model;

    protected string $integrationSid;
    protected string $companySid;
    protected string $configUrl;

    public function __construct(string $domain, string $authToken, string $companySid, string $integrationSid, string $configUrl)
    {
        parent::__construct($domain, $authToken);
        $this->companySid = $companySid;
        $this->integrationSid = $integrationSid;
        $this->configUrl = $configUrl;
    }

    protected function setRequest(string $method, string $route, array $body): AuthServiceRequest
    {
        return new AuthServiceRequest($this->authToken, $method, $this->domain.$route, $body);
    }

    protected function getModel(Response $response)
    {
        if(!isset($this->model) || !isset($this->model->access_token))
            $this->model = parent::getModel($response);

        return $this->model;
    }

    protected function searchTokenRequestBody(): array
    {
        return [
            'integration_sid' => $this->integrationSid,
            'company_sid' => $this->companySid,
        ];
    }

    protected function setTokenRequestBody(InstallRequest $request): array
    {
        return [
            'integration_sid' =>  $this->integrationSid,
            'company_sid' => $this->companySid,
            'integration_id' => AMO_INTEGRATION_ID,
            'secret_key' => AMO_INTEGRATION_SECRET_KEY,
            'service' => 'amocrm',
            'authorization_code' => $request->code,
            'url' => $this->configUrl,
            'redirect_url' => AMO_INTEGRATION_REDIRECT_URL
        ];
    }

    public function install(InstallRequest $request): AuthTokenModel
    {
        if(!isset($this->get()->access_token))
            return $this->set($request);
        else
            return $this->update($request);

    }

    public function get()
    {
        $body =  $this->searchTokenRequestBody();
        $response = $this->getResponse('GET', $body, [200, 201]);
        return $this->getModel($response);
    }

    public function set(InstallRequest $request): AuthTokenModel
    {
        $body = $this->setTokenRequestBody($request);
        $response = $this->getResponse('POST', '/token', $body);
        return $this->getModel($response);
    }

    public function update(InstallRequest $request): AuthTokenModel
    {
        $body = $this->setTokenRequestBody($request);
        $response = $this->getResponse('PATCH', '/token', $body);
        return $this->getModel($response);
    }

    public function delete(): bool
    {
        $body = $this->searchTokenRequestBody();
        $body['delete'] = true;

        try {
            $this->getResponse('DELETE', '/token', $body);
            return true;
        } catch (ComponentException $exception){
            return false;
        }
    }
}