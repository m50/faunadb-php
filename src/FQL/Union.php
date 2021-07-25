<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#sets).
 *
 * @param mixed ...$sets
 *   A list of SetRefs to union together.
 * @return Expr
 */
function Union(mixed ...$sets): Expr
{
    assertAllIsExprArg($sets);

    return new Expr(['union' => wrap(varargs($sets))]);
}
