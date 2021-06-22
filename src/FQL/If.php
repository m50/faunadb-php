<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @psalm-param ExprArg $condition
 * @psalm-param ExprArg $then
 * @psalm-param ExprArg $else
 * @return Expr
 */
function IfFunc(mixed $condition, mixed $then, mixed $else = null): Expr
{
    assertIsExprArg($condition);
    assertIsExprArg($then);
    assertIsExprArg($else, true);

    return new Expr([
        'if' => wrap($condition),
        'then' => wrap($then),
        'else' => wrap($else),
    ]);
}
