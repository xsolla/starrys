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
        $message .= 'Response: ' . print_r($response, true) . PHP_EOL;
        if (null !== $path) {
            $message .= 'Path: ' . print_r($path, true);
        }

        parent::__construct($message, $code, $previous);
    }
}