<?php

declare(strict_types=1);

namespace FaunaDB\Exceptions;

final class NotAnExprArgException extends BaseException
{
    public static function withArg($arg): static
    {
        $type = gettype($arg);
        return new static("Argument of type '{$type}' not of ExprArg Type.", 500);
    }
}
