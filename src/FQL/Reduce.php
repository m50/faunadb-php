<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://docs.fauna.com/fauna/current/api/fql/functions/reduce).
 *
 * @param mixed lambda The accumulator function
 * @param mixed initial The initial value
 * @param mixed collection The colleciton to be reduced
 * @return Expr
 */
function Reduce(mixed $lambda, mixed $initial, mixed $collection): Expr
{
    assertIsExprArg($lambda);
    assertIsExprArg($initial);
    assertIsExprArg($collection);

    return new Expr([
        'reduce' => wrap($lambda),
        'initial' => wrap($initial),
        'collection' => wrap($collection),
    ]);
}
