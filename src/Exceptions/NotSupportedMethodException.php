<?php

namespace ThinkToShare\Payment\Exceptions;

use Exception;

class NotSupportedMethodException extends Exception
{
    public function __construct(string $method, string $gateway)
    {
        parent::__construct("{$method} not supported in [{$gateway}] Gateway.");
    }
}
