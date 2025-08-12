<?php

namespace App\Exceptions;

class TokenException extends AuthException
{
    public function __construct(string $message = 'Token operation failed', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}