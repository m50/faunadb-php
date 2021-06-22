<?php

declare(strict_types=1);

namespace FaunaDB\Client;

use FaunaDB\Expr\Expr;
use InvalidArgumentException;

use function function_exists;

/**
 * @method static Expr Abort(string $msg)
 * @method static Expr At(DateTimeInterface|int $time, mixed $expr)
 * @method static Expr Bytes(string $bytes)
 * @method static Expr Do(...$args)
 * @method static Expr DoFunc(...$args)
 * @method static Expr If(mixed $condition, mixed $then, mixed $else = null)
 * @method static Expr IfFunc(mixed $condition, mixed $then, mixed $else = null)
 * @method static Expr Lambda(string $msg)
 * @method static Expr Let($vars, mixed $expr)
 * @method static Expr Ref(mixed $ref, ?string $id = null)
 * @method static Expr Var(string $varName)
 */
final class Query
{
    private const SPECIAL_CASES = [
        'Do' => 'DoFunc',
        'If' => 'IfFunc',
        'Var' => 'VarFunc',
        'Foreach' => 'ForeachFunc',
        'IsSet' => 'IsSetFunc',
    ];

    private function __construct()
    {
        // This is a static class.
    }

    public static function __callStatic(string $name, array $arguments): Expr
    {
        $func = static::SPECIAL_CASES[$name] ?? $name;
        $func = "\\FaunaDB\\FQL\\{$func}";

        if (!function_exists($func)) {
            throw new InvalidArgumentException("FQL Function {$func} does not exist.");
        }

        return $func(...$arguments);
    }
}
