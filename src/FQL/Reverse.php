<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @psalm-return Expr
 */
function Reverse(mixed $expr): Expr
{
    return new Expr(['reverse' => wrap($expr)]);
}
