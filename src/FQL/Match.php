<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#sets).
 *
 * @param mixed $index
 *   The Ref of the index to match against.
 * @param mixed ...$terms
 *   A list of terms used in the match.
 * @return Expr
 */
function MatchFunc(mixed $index, mixed ...$terms): Expr
{
    assertIsExprArg($index);
    assertAllIsExprArg($terms);

    return new Expr(['match' => wrap($index), 'terms' => wrap(varargs($terms))]);
}
