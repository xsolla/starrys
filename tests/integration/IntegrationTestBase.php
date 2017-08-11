<?php

namespace Platron\Starrys\tests\integration;

use Platron\Starrys\tests\integration\MerchantSettings;

class IntegrationTestBase extends \PHPUnit_Framework_TestCase {

	/** @var sting Адрес для запросов */
	protected $starrysApiUrl;
    /** @var string Путь до приватного ключа */
    protected $secretKeyPath;
    /** @var string Путь до сертифката */
    protected $certPath;
    
    public function __construct() {
		$this->starrysApiUrl = MerchantSettings::API_STARRYS_URL;
        $this->secretKeyPath = 'tests/integration/merchant_data/'.MerchantSettings::SECRET_KEY_NAME;
        $this->certPath = 'tests/integration/merchant_data/'.MerchantSettings::CERT_NAME;
    }
}
