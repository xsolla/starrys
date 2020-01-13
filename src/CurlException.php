<?php

namespace Platron\Starrys;

use Throwable;

class CurlException extends \RuntimeException
{
    public function __construct($logInfo, $jsonError, $code = 0, Throwable $previous = null)
    {
        $message = 'Curl error: ' . $jsonError . PHP_EOL;
        $message .= $logInfo;
        parent::__construct($message, $code, $previous);
    }
}
