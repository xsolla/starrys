<?php

namespace Platron\Starrys;

use Throwable;

class ServerErrorException extends \RuntimeException
{
    public function __construct($logInfo, $code = 0, Throwable $previous = null)
    {
        $message = 'Server response code: ' . $code . PHP_EOL;
        $message .= $logInfo;
        parent::__construct($message, $code, $previous);
    }
}