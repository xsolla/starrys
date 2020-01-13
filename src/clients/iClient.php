<?php

namespace Platron\Starrys\clients;

use Platron\Starrys\services\BaseServiceRequest;

interface iClient
{
    /**
     * Послать запрос
     * @param BaseServiceRequest $service
     */
    public function sendRequest(BaseServiceRequest $service);
}