<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use FaunaDB\Result\Collection;
use function is_array;
use function is_callable;

/**
 * @psalm-param Collection<string,ExprArg>|array<string,ExprArg> $vars
 * @psalm-param ExprArg $expr
 * @psalm-return Expr
 * @psalm-pure
 */
function Let(Collection|array $vars, mixed $expr): Expr
{
    assertIsExprArg($expr);
    
    $bindings = Collection::empty();
    if (is_array($vars)) {
        $vars = Collection::from($vars);
    }
    $vars = $vars->filterNull();

    $bindings = $vars->map(fn ($v, $k) => [$k => wrap($v)]);

    if (is_callable($expr)) {
        $exprVars = $vars->map(fn ($v, $k) => VarFunc($k));
        /** @var  */
        $expr = call_user_func_array($expr, $exprVars->toArray());
    }

    return new Expr(['let' => $bindings->toArray(), 'in' => wrap($expr)]);
}
