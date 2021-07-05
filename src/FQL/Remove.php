<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param mixed $ref The Ref to insert against.
 * @param mixed $ts The valid time of the inserted event
 * @param mixed $action Whether the event should be a Create, Update, or Delete.
 * @return Expr
 */
function Remove(mixed $ref, mixed $ts, mixed $action): Expr
{
    assertIsExprArg($ref);
    assertIsExprArg($ts);
    assertIsExprArg($action);

    return new Expr([
        'remove' => wrap($ref),
        'ts' => wrap($ts),
        'action' => wrap($action),
    ]);
}
