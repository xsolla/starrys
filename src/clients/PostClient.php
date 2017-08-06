<?php

namespace Platron\Starrys\clients;

use Platron\Starrys\clients\iClient;
use Platron\Starrys\SdkException;
use Platron\Starrys\services\BaseServiceRequest;
use Psr\Log\LoggerInterface;
use stdClass;

class PostClient implements iClient {
    
    const LOG_LEVEL = 0;
    
    /** @var string путь до приватного ключа */
    protected $secretKeyPath;
    /** @var string путь до сертификата */
    protected $certPath;

    /** @var LoggerInterface */
    protected $logger;
    /** @var int */
    protected $connectionTimeout = 30;
    
    /**
     * Секретный ключ для подписи запросов
     * @param string $secretKeyPath
     */
    public function __construct($secretKeyPath, $certPath){
        $this->secretKeyPath = $secretKeyPath;
        $this->certPath = $certPath;
    }
    
    /**
     * Установить логер
     * @param LoggerInterface $logger
     * @return self
     */
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
        return $this;
    }
    
    /**
     * Установка максимального времени ожидания
     * @param int $connectionTimeout
     * @return self
     */
    public function setConnectionTimeout($connectionTimeout){
        $this->connectionTimeout = $connectionTimeout;
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function sendRequest(BaseServiceRequest $service) {       
        $requestParameters = $service->getParameters();
        $requestUrl = $service->getRequestUrl();
        
        $curl = curl_init($requestUrl);
        if(!empty($requestParameters)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestParameters));
        }
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSLKEY, $this->secretKeyPath);
        curl_setopt($curl, CURLOPT_SSLCERT, $this->certPath);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
        
        $response = curl_exec($curl);
        
        if($this->logger){
            $this->logger->log(self::LOG_LEVEL, 'Requested url '.$requestUrl.' params '. json_encode($requestParameters));
            $this->logger->log(self::LOG_LEVEL, 'Response '.$response);
        }
        	
		if(curl_errno($curl)){
			throw new SdkException(curl_error($curl), curl_errno($curl));
		}

		return $response ? json_decode($response) : new stdClass();
    }

}
