<?php

namespace Platron\Shtrihm\tests\integration;

use Platron\Shtrihm\tests\integration\MerchantSettings;

class IntegrationTestBase extends \PHPUnit_Framework_TestCase {

    /** @var string */
    protected $group;
    /** @var string Путь до приватного ключа */
    protected $secretKeyPath;
    /** @var string Путь до сертифката */
    protected $certPath;
    
    public function __construct() {
        $this->group = MerchantSettings::GROUP;
        $this->secretKeyPath = 'tests/integration/merchant_data/'.MerchantSettings::SECRET_KEY_NAME;
        $this->certPath = 'tests/integration/merchant_data/'.MerchantSettings::CERT_NAME;
    }
}
