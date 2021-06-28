<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

function Call(mixed $ref, mixed ...$args): Expr
{
    assertIsExprArg($ref);
    assertAllIsExprArg($args);

    return new Expr(['call' => wrap($ref), 'arguments' => wrap($args)]);
}
