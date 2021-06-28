<?php

declare(strict_types=1);

namespace FaunaDB\Exceptions;

final class ImmutableException extends BaseException
{
    public static function withKeyAndValue(string|int $key, mixed $value): self
    {
        $value = (string) $value;

        return new static("Unable to set '{$key}' with <{$value}> because it is immutable.");
    }

    public static function withKey(string|int $key): self
    {
        $key = (string) $key;

        return new static("Unable to set '{$key}' because it is immutable.");
    }
}
