<?php

declare(strict_types=1);

namespace FaunaDB\Exceptions;

final class NotAnExprArgException extends BaseException
{
    /**
     * @param callable|string $arg
     */
    public static function withArg(callable|string $arg): static
    {
        $type = gettype($arg);
        return new static("Argument of type '{$type}' not of ExprArg Type.", 500);
    }
}
