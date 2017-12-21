<?php

namespace Platron\Starrys;

use Throwable;

class CurlException extends \RuntimeException
{
    public function __construct($logInfo, $curlError, $code = 0, Throwable $previous = null)
    {
        $message = 'Curl error: ' . $curlError . PHP_EOL;
        $message .= $logInfo;
        parent::__construct($message, $code, $previous);
    }
}
