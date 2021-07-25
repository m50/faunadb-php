<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#sets).
 *
 * @param mixed $ref
 *   The Ref of the document for which to retrieve the singleton set.
 * @return Expr
 */
function Singleton(mixed $ref): Expr
{
    assertIsExprArg($ref);

    return new Expr(['singleton' => wrap($ref)]);
}
