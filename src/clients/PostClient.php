<?php

namespace Platron\Starrys\clients;

use Platron\Starrys\clients\iClient;
use Platron\Starrys\CurlException;
use Platron\Starrys\ServerErrorException;
use Platron\Starrys\services\BaseServiceRequest;
use stdClass;

class PostClient implements iClient
{

    /** @var string */
    protected $url;
    /** @var string путь до приватного ключа */
    protected $secretKeyPath;
    /** @var string путь до сертификата */
    protected $certPath;
    /** @var string информация для логирования */
    protected $logInfo;

    /** @var int */
    protected $connectionTimeout = 30;

    /**
     * Секретный ключ для подписи запросов
     * @param string $url Путь для запросов https://<адрес, указанный в личном кабинете>:<порт, указанный в личном кабинете>
     * @param string $secretKeyPath
     * @param string $certPath
     */
    public function __construct($url, $secretKeyPath, $certPath)
    {
        $this->url = $url;
        $this->secretKeyPath = $secretKeyPath;
        $this->certPath = $certPath;
    }

    /**
     * Установка максимального времени ожидания
     * @param int $connectionTimeout
     * @return self
     */
    public function setConnectionTimeout($connectionTimeout)
    {
        $this->connectionTimeout = $connectionTimeout;
        return $this;
    }

    /**
     *  Получение залогированной информации
     * @return string
     */
    public function getLogInfo()
    {
        return $this->logInfo;
    }

    /**
     * @inheritdoc
     * @throws \Platron\Starrys\ServerErrorException
     * @throws \Platron\Starrys\CurlException
     */
    public function sendRequest(BaseServiceRequest $service)
    {
        $requestParameters = $service->getParameters();
        $requestUrl = $this->url . $service->getUrlPath();

        $curl = curl_init($requestUrl);
        $this->addRequestParameters($requestParameters, $curl);
        $this->addSslAuthentication($curl);

        $jsonResponse = curl_exec($curl);

        $this->fillLogInfo($requestUrl, $requestParameters, $jsonResponse);
        $this->validateCurlResponse($curl);

        return json_decode($jsonResponse);
    }

    /**
     * @param $requestUrl
     * @param $requestParameters
     * @param $response
     */
    private function fillLogInfo($requestUrl, $requestParameters, $response)
    {
        $this->logInfo = 'Requested url ' . $requestUrl . ' params ' . json_encode($requestParameters) . PHP_EOL;
        $this->logInfo .= 'Response ' . $response;
    }

    /**
     * @param $requestParameters
     * @param $curl
     */
    private function addRequestParameters($requestParameters, $curl)
    {
        if (!empty($requestParameters)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestParameters));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
    }

    /**
     * @param $curl
     */
    private function addSslAuthentication($curl)
    {
        if (!empty($this->secretKeyPath) && !empty($this->certPath)) {
            curl_setopt($curl, CURLOPT_SSLKEY, $this->secretKeyPath);
            curl_setopt($curl, CURLOPT_SSLCERT, $this->certPath);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }
    }

    /**
     * @param $curl
     * @throws \Platron\Starrys\ServerErrorException
     * @throws \Platron\Starrys\CurlException
     */
    private function validateCurlResponse($curl)
    {
        if (curl_errno($curl)) {
            throw new CurlException($this->logInfo, curl_error($curl), curl_errno($curl));
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new ServerErrorException($this->logInfo, $httpCode);
        }
    }

}
