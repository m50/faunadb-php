<?php

declare(strict_types=1);

namespace FaunaDB\Exceptions;

final class InvalidConfigurationException extends BaseException
{
    public static function withInvalidSecret(): self
    {
        return new self('Invalid or no FaunaDB secret provided.', 500);
    }
}
