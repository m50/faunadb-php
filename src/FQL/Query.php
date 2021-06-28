<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

function Query(mixed $lambda): Expr
{
    assertIsExprArg($lambda);

    return new Expr(['query' => wrap($lambda)]);
}
