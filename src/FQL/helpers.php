<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Exceptions\NotAnExprArgException;
use FaunaDB\Expr\Expr;
use FaunaDB\Interfaces\Arrayable;
use FaunaDB\Result\Collection;
use ReflectionFunction;

/**
 * @psalm-type ExprVal = Expr | string | int | float | boolean | array<string,mixed> | Collection
 * @psalm-type ExprArg = ExprVal | ExprVal[] | callable(ExprArg...):Expr
 */

function isExprCallable(mixed $arg): bool
{
    if (!is_callable($arg)) {
        return false;
    }

    $arg = new ReflectionFunction($arg);
    $retType = $arg->getReturnType();

    if ($retType === null) {
        return true;
    }

    return isExprType($retType->getName(), true);
}

/**
 * @psalm-return $arg is ExprArg
 */
function isExprArg(mixed $arg, bool $allowNull = false): bool
{
    if ($allowNull && $arg === null) {
        return true;
    }

    if (is_array($arg)) {
        if (Collection::from($arg)->isObject()) {
            return true;
        } else {
            foreach ($arg as $obj) {
                if (!isExprArg($obj)) {
                    return false;
                }
            }

            return true;
        }
    } elseif ($arg instanceof Collection) {
        if ($arg->isObject()) {
            return true;
        } else {
            foreach ($arg as $obj) {
                if (!isExprArg($obj)) {
                    return false;
                }
            }

            return true;
        }
    }

    return $arg instanceof Expr ||
        is_string($arg) ||
        is_numeric($arg) ||
        is_bool($arg) ||
        isExprCallable($arg);
}

function isExprType(string $type, bool $allowNull = false): bool
{
    if ($allowNull && $type === 'null') {
        return true;
    }

    return in_array($type, [
        'string',
        'int',
        'float',
        'bool',
        Expr::class,
        Collection::class,
        'array'
    ], true);
}

function assertIsExprArg(mixed $arg, bool $allowNull = false): void
{
    if (!isExprArg($arg, $allowNull)) {
        throw NotAnExprArgException::withArg($arg);
    }
}

function assertAllIsExprArg(array $args, bool $allowNull = false): void
{
    foreach ($args as $arg) {
        assertIsExprArg($arg, $allowNull);
    }
}


/**
 * @psalm-param ExprArg|null $val
 * @psalm-return ExprArg|null
 * @psalm-pure
 */
function wrap(mixed $val)
{
    if ($val === null) {
        return null;
    } elseif ($val instanceof Expr) {
        return $val;
    } elseif (is_callable($val) && !is_string($val) && !is_array($val)) {
        return Lambda($val);
    } elseif (\is_array($val) || $val instanceof Arrayable) {
        $val = Collection::from($val);
        if ($val->hasOnlyNumericKeys()) {
            return new Expr(wrapValues($val));
        } elseif ($val->isObject()) {
            return new Expr(['object' => wrapValues($val->toArray())]);
        }
    }

    return $val;
}

function wrapValues(null|array|Arrayable $val): array
{
    if ($val === null) {
        return null;
    }

    return Collection::from($val)->map(fn ($v) => wrap($v))->toArray();
}

function params(array $mainParams, array $optionalParams = [])
{
    return array_merge($mainParams, array_filter($optionalParams, fn ($v) => $v !== null));
}
