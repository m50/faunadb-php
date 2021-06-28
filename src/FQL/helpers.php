<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use Closure;
use FaunaDB\Exceptions\NotAnExprArgException;
use FaunaDB\Expr\Expr;
use FaunaDB\Interfaces\Arrayable;
use FaunaDB\Result\Collection;
use ReflectionFunction;

function isExprCallable(mixed $arg): bool
{
    if (! is_callable($arg)) {
        return false;
    }

    $arg = Closure::fromCallable($arg);

    $arg = new ReflectionFunction($arg);
    $retType = $arg->getReturnType();

    if ($retType === null) {
        return true;
    }

    /** @var string $typeName */
    $typeName = $retType->getName();

    return isExprType($typeName, true);
}

function isExprArg(mixed $arg, bool $allowNull = false): bool
{
    if ($allowNull && $arg === null) {
        return true;
    }

    if (is_array($arg)) {
        if (Collection::from($arg)->isObject()) {
            return true;
        } else {
            /** @var mixed $obj */
            foreach ($arg as $obj) {
                if (! isExprArg($obj)) {
                    return false;
                }
            }

            return true;
        }
    } elseif ($arg instanceof Collection) {
        if ($arg->isObject()) {
            return true;
        } else {
            /** @var mixed $obj */
            foreach ($arg as $obj) {
                if (! isExprArg($obj)) {
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
    if (! isExprArg($arg, $allowNull)) {
        throw NotAnExprArgException::withArg((string) $arg);
    }
}

function assertAllIsExprArg(array $args, bool $allowNull = false): void
{
    /** @var mixed $arg */
    foreach ($args as $arg) {
        assertIsExprArg($arg, $allowNull);
    }
}


function wrap(mixed $val): mixed
{
    if ($val === null) {
        return null;
    } elseif ($val instanceof Expr) {
        return $val;
    } elseif (is_callable($val) && ! is_string($val) && ! is_array($val)) {
        return Lambda($val);
    } elseif (\is_array($val) || $val instanceof Arrayable) {
        /** @psalm-var mixed[]|Arrayable<array-key,mixed> $val */
        $objVals = Collection::from($val);
        if ($objVals->hasOnlyNumericKeys()) {
            return wrapValues($objVals);
        } elseif ($objVals->isObject()) {
            return new Expr(['object' => wrapValues($objVals->toArray())]);
        }
    }

    return $val;
}

function wrapValues(null|array|Arrayable $val): ?array
{
    if ($val === null) {
        return null;
    }

    return Collection::from($val)->map(fn (mixed $v): mixed => wrap($v))->toArray();
}

/**
 * @param array<string,mixed> $mainParams
 * @param array<string,mixed> $optionalParams
 * @return array<string,mixed>
 */
function params(array $mainParams, array $optionalParams = []): array
{
    return array_merge($mainParams, array_filter($optionalParams, fn ($v) => $v !== null));
}

function varargs(array|Arrayable $args): mixed
{
    $args = Collection::from($args);
    if (! $args->hasOnlyNumericKeys()) {
        $args = $args->values();
    }

    return $args->count() === 1 ? $args->first() : $args->toArray();
}
