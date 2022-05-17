<?php

namespace app\src\integration\components;

use app\src\integration\request\ChatRequest;


class Chat extends Component
{
    protected string $modelClass = 'app\src\integration\models\ChatModel';
    protected string $amojoId;

    public function __construct(string $domain, string $amojoId)
    {
        $this->amojoId = $amojoId;
        parent::__construct($domain, self::channelSecret($domain));
    }

    protected function setRequest(string $method, string $route, array $body): ChatRequest
    {
        return new ChatRequest($this->authToken, $method, self::amojoServer($this->domain), $route, $body);
    }

    public function connect()
    {
        $response = $this->getResponse(
            'POST',
            sprintf('/v2/origin/custom/%s/connect', self::channelId($this->domain)),
            [
                'account_id' => $this->amojoId,
                'title' => 'Send SMS',
                'hook_api_version' => 'v2'
            ]
        );

        return $this->getModel($response);
    }

    protected static function amojoServer($domain): string
    {
        return
            (is_bool(stripos($domain, '.amocrm.ru')))
                ? 'https://amojo.amocrm.com'
                : 'https://amojo.amocrm.ru';
    }

    protected static function channelId($domain): string
    {
        return
            (is_bool(stripos($domain, '.amocrm.ru')))
                ? COM_CHAT_CHANNEL_ID
                : RU_CHAT_CHANNEL_ID;
    }

    protected static function channelSecret($domain): string
    {
        return
            (is_bool(stripos($domain, '.amocrm.ru')))
                ? COM_CHAT_SECRET_KEY
                : RU_CHAT_SECRET_KEY;
    }
}