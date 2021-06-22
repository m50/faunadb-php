<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use ReflectionFunction;
use ReflectionParameter;
use InvalidArgumentException;
use FaunaDB\Result\Collection;
use FaunaDB\Exceptions\NotAnExprArgException;
use FaunaDB\Interfaces\Arrayable;

/**
 * @psalm-param Expr|callable(ExprArg...):ExprArg $func
 */
function Lambda(callable|Expr $func, null|array|Arrayable $varNames = null): Expr
{
    if ($func instanceof Expr) {
        if ($varNames !== null) {
            $varNames = Collection::from($varNames)
                ->filterNull()
                ->map(fn ($v) => \str_replace('$', '', $v));

            if (!$varNames->isList() || $varNames->isEmpty()) {
                throw new InvalidArgumentException(
                    'Provided VarNames must be a list and must contain at least one value.'
                );
            } elseif ($varNames->count() === 1) {
                $varNames = $varNames->first();
            }

            return new Expr(['lambda' => wrap($varNames), 'expr' => wrap($func)]);
        }
        return $func;
    }
    if (!isExprCallable($func)) {
        throw NotAnExprArgException::withArg($func);
    }

    $method = new ReflectionFunction($func);
    $params = Collection::from($method->getParameters());
    if ($params->count() < 1) {
        throw new InvalidArgumentException('Provided function must take at least 1 arguement.');
    }
    /** @var \ReflectionParameter $param */
    foreach ($params as $param) {
        $paramType = $param->getType();
        if ($paramType !== null && $paramType->getName() !== Expr::class) {
            throw NotAnExprArgException::withArg($param->getName());
        }
    }

    $paramNames = $params->map(fn (ReflectionParameter $param) => $param->getName());
    $paramVars = $paramNames->map(fn (string $name) => VarFunc($name));

    $expr = call_user_func_array($func, $paramVars->toArray());

    assertIsExprArg($expr, true);

    $lambdaVal = $paramNames->toArray();
    if ($paramNames->count() === 1) {
        $lambdaVal = $paramNames->first();
    }

    return new Expr(['lambda' => wrap($lambdaVal), 'expr' => wrap($expr)]);
}
