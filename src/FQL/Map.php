<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @param mixed $collection An expression resulting in a collection to be mapped over.
 * @param mixed $lambdaExpr A function to be called for each element of the collection.
 */
function Map(mixed $collection, mixed $lambdaExpr): Expr
{
    assertIsExprArg($collection);
    assertIsExprArg($lambdaExpr);

    return new Expr(['map' => wrap($lambdaExpr), 'collection' => wrap($collection)]);
}
