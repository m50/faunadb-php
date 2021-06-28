<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

function AccessProvider(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['access_provider' => wrap($expr)]);
}
