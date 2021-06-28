<?php

declare(strict_types=1);

namespace FaunaDB\Client;

use FaunaDB\Expr\Expr;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

use function function_exists;

final class Query
{
    private function __construct()
    {
        // This is a static class.
    }

    public static function __callStatic(string $name, array $arguments): Expr
    {
        $namespace = '\\FaunaDB\\FQL\\';
        $func = $namespace . $name . 'Func';

        // If real param doesn't end with `Func`, then use real name
        if (! function_exists($func)) {
            $func = "{$namespace}{$name}";
        }

        if (! function_exists($func)) {
            throw new InvalidArgumentException("FQL Function {$func} does not exist.");
        }

        $result = $func(...$arguments);
        Assert::isInstanceOf($result, Expr::class);

        return $result;
    }
}
