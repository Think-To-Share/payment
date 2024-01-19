<?php

namespace ThinkToShare\Payment\Exceptions;

use Exception;

class VerifySignatureException extends Exception
{
    public function __construct(string $signature)
    {
        parent::__construct("[$signature] is not a valid signature.");
    }
}
