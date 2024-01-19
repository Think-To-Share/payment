<?php

namespace ThinkToShare\Payment\Exceptions;

use Exception;

class EncryptionException extends Exception
{
    public function __construct(string $data)
    {
        parent::__construct("There was problem encrypting data: {$data}.");
    }
}
