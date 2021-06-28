<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use FaunaDB\Result\Collection;
use Webmozart\Assert\Assert;

use function is_array;
use function is_callable;

/**
 * @psalm-param Collection<string,mixed>|array<string,mixed> $vars
 * @return Expr
 */
function Let(Collection|array $vars, mixed $expr): Expr
{
    assertIsExprArg($expr);

    $vars = Collection::from($vars)->filterNull();
    Assert::allString(\array_keys($vars->toArray()));

    $bindings = $vars->map(fn (mixed $v, string $k) => [$k => wrap($v)]);

    if (is_callable($expr)) {
        $exprVars = $vars->map(fn (mixed $_, string $k): Expr => VarFunc($k));
        /** @var Expr $resultExpr */
        $resultExpr = call_user_func_array($expr, $exprVars->toArray());
        $expr = $resultExpr;
    }

    return new Expr(['let' => $bindings->toArray(), 'in' => wrap($expr)]);
}
