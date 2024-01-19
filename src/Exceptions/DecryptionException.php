<?php

namespace ThinkToShare\Payment\Exceptions;

use Exception;

class DecryptionException extends Exception
{
    public function __construct(string $data)
    {
        parent::__construct("There was problem decrypting data: {$data}.");
    }
}
