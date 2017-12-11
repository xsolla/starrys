<?php

namespace Platron\Starrys\clients;

use Platron\Starrys\services\BaseServiceRequest;

interface iClient
{

    /**
     * Послать запрос
     * @param \Platron\Starrys\BaseService $service
     */
    public function sendRequest(BaseServiceRequest $service);
}
