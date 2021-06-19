<?php

declare(strict_types=1);

namespace FaunaDB\Exceptions;

final class ImmutableException
{
    public static function withKeyAndValue(string|int $key, $value): self
    {
        return new self("Unable to set '{$key}' with <{$value}> because it is immutable.");
    }

    public static function withKey(string|int $key): self
    {
        return new self("Unable to set '{$key}' because it is immutable.");
    }
}
