<?php

namespace App\Domains\Transfer\Exceptions;

use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public function __construct(string $message = 'Insufficient stock in the source warehouse.')
    {
        parent::__construct($message);
    }
}
