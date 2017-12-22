<?php

namespace Platron\Starrys;

use Throwable;

class InvalidJsonException extends \RuntimeException
{
    public function __construct($logInfo, $jsonError, $code = 0, Throwable $previous = null)
    {
        $message = 'Json decode error: ' . $jsonError . PHP_EOL;
        $message .= $logInfo;
        parent::__construct($message, $code, $previous);
    }
}