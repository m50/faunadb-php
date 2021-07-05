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
 * @param mixed $params An object representing the parameters of the document.
 * @return Expr
 */
function Insert(mixed $ref, mixed $ts, mixed $action, mixed $params): Expr
{
    assertIsExprArg($ref);
    assertIsExprArg($ts);
    assertIsExprArg($action);
    assertIsExprArg($params);

    return new Expr([
        'insert' => wrap($ref),
        'ts' => wrap($ts),
        'action' => wrap($action),
        'params' => wrap($params),
    ]);
}
