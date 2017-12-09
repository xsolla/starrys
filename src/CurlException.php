<?php

namespace Platron\Starrys;

use Throwable;

class CurlException extends \RuntimeException
{
    public function __construct($logInfo, $curlError, $code = 0, Throwable $previous = null)
    {
        $message = $logInfo . PHP_EOL;
        $message .= 'Curl error:' . $curlError;
        parent::__construct($message, $code, $previous);
    }
}
