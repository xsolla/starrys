<?php

namespace Platron\Starrys\services;

class FDOExchangeStatusRequest extends BaseServiceRequest
{
    const PASSWORD = 30;

    /**
     * @inheritdoc
     */
    public function getUrlPath()
    {
        return '/fr/api/v2/GetFDOExchangeStatus';
    }
}