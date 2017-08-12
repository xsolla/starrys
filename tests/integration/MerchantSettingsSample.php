<?php

namespace Platron\Starrys\tests\integration;

use Platron\Starrys\services\ComplexRequest;

class MerchantSettings {
    const 
		TAX_MODE = ComplexRequest::TAX_MODE_OSN,
        SECRET_KEY_NAME = 'client.key',
        CERT_NAME = 'client.crt',
		API_STARRYS_URL = 'https://fce.starrys.ru:4443';
}
