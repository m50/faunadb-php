<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param mixed $ref The Ref to delete.
 * @return Expr
 */
function Delete(mixed $ref): Expr
{
    assertIsExprArg($ref);

    return new Expr(['delete' => wrap($ref)]);
}
