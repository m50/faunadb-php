<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#sets).
 *
 * @param mixed $refSet
 *   A Ref or SetRef to retrieve an event set from.
 * @return Expr
 */
function Events(mixed $refSet): Expr
{
    assertIsExprArg($refSet);

    return new Expr(['events' => wrap($refSet)]);
}
