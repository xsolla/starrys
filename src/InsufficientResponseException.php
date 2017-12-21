<?php

namespace Platron\Starrys;

use Throwable;

class InsufficientResponseException extends \InvalidArgumentException
{
    public function __construct(
        $response,
        $missedProperty,
        array $path = null,
        $code = 0,
        Throwable $previous = null
    ) {
        $message = 'Cannot find property ' . $missedProperty . PHP_EOL;
        $message .= 'Response: ' . json_encode($response) . PHP_EOL;
        if (null !== $path) {
            $message .= 'Path: ' . json_encode($path);
        }

        parent::__construct($message, $code, $previous);
    }
}