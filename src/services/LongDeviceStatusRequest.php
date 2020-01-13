<?php

namespace Platron\Starrys\services;

class LongDeviceStatusRequest extends BaseServiceRequest
{
    const PASSWORD = 1;

    /**
     * @inheritdoc
     */
    public function getUrlPath()
    {
        return '/fr/api/v2/LongDeviceStatus';
    }
}