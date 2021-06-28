<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use Closure;
use FaunaDB\Expr\Expr;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use InvalidArgumentException;
use FaunaDB\Result\Collection;
use FaunaDB\Interfaces\Arrayable;
use FaunaDB\Exceptions\NotAnExprArgException;

/**
 * @psalm-param Expr|callable(mixed...):mixed $func
 * @psalm-param null|list<string>|Arrayable<int,string> $varNames
 */
function Lambda(callable|Expr $func, null|array|Arrayable $varNames = null): Expr
{
    if ($func instanceof Expr) {
        if ($varNames !== null) {
            $varNames = Collection::from($varNames)
                ->filterNull()
                ->map(fn (string $v): string => \str_replace('$', '', $v));

            if (! $varNames->isList() || $varNames->isEmpty()) {
                throw new InvalidArgumentException(
                    'Provided VarNames must be a list and must contain at least one value.'
                );
            }

            return new Expr(['lambda' => wrap(varargs($varNames)), 'expr' => wrap($func)]);
        }

        return $func;
    }
    if (! isExprCallable($func)) {
        throw NotAnExprArgException::withArg($func);
    }

    $method = new ReflectionFunction(Closure::fromCallable($func));
    $params = Collection::from($method->getParameters());
    if ($params->count() < 1) {
        throw new InvalidArgumentException('Provided function must take at least 1 arguement.');
    }
    /** @var \ReflectionParameter $param */
    foreach ($params as $param) {
        $paramType = $param->getType();
        if ($paramType instanceof ReflectionNamedType && $paramType->getName() !== Expr::class) {
            throw NotAnExprArgException::withArg($param->getName());
        }
    }

    $paramNames = $params->map(fn (ReflectionParameter $param) => $param->getName());
    $paramVars = $paramNames->map(fn (string $name) => VarFunc($name));

    /** @var mixed $expr */
    $expr = call_user_func_array($func, $paramVars->toArray());

    assertIsExprArg($expr, true);

    return new Expr(['lambda' => wrap(varargs($paramNames)), 'expr' => wrap($expr)]);
}
