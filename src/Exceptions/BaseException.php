<?php

declare(strict_types=1);

namespace FaunaDB\Exceptions;

use Exception;
use Throwable;

abstract class BaseException extends Exception
{
    final protected function __construct(string $message = '', int $code = 0, ?Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }

    public function withPrevious(Throwable $prev): static
    {
        return new static($this->message, $this->code, $prev);
    }
}
