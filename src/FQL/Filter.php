<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @param mixed $collection An expression resulting in a collection to be filtered.
 * @param mixed $lambda Expr A function that returns a boolean used to filter unwanted values.
 */
function Filter(mixed $collection, mixed $lambdaExpr): Expr
{
    assertIsExprArg($collection);
    assertIsExprArg($lambdaExpr);

    return new Expr(['filter' => wrap($lambdaExpr), 'collection' => wrap($collection)]);
}
