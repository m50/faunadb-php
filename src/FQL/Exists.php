<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use DateTimeInterface;
use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#read-functions).
 *
 * @param mixed ref An expression resulting in a Ref.
 * @param mixed ts The snapshot time at which to check for the document's existence.
 *   This may be an instance of DateTimeInterface as well.
 * @return Expr
 */
function Exists(mixed $ref, mixed $ts = null): Expr
{
    assertIsExprArg($ref);
    assertIsExprArg($ts, true);

    return new Expr(params(['exists' => wrap($ref)], ['ts' => wrap($ts)]));
}
