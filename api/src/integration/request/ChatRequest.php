<?php


namespace app\src\integration\request;

use yii\helpers\Json;

class ChatRequest extends BaseRequest
{
    private string $date;
    private string $checksum;
    private string $xSignature;

    public function __construct($authToken, $method, $domain, $route, $body = null)
    {
        $this->date = date('r', time());
        $this->checksum = strtolower(md5(Json::encode($body)));
        $this->xSignature = $this->setXSignature($method, $route, $authToken);

        parent::__construct($authToken, $method, $domain.$route, $body);
    }

    public function setHeaders($headers): array
    {
        return parent::setHeaders([
            'Date' => $this->date,
            'Content-MD5' => $this->checksum,
            'X-Signature' => $this->xSignature
        ]);
    }

    public function setXSignature($method, $url, $secret): string
    {
        $str = implode("\n", [
            strtoupper($method),
            $this->checksum,
            'application/json',
            $this->date,
            $url,
        ]);

        return strtolower(hash_hmac('sha1', $str, $secret));
    }

}