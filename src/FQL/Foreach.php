<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @param ExprArg $collection An expression resulting in a collection to be iterated over.
 * @param ExprArg $lambdaExpr A function to be called for each element of the collection.
 * @psalm-param ExprArg $collection
 * @psalm-param ExprArg $lambdaExpr
 * @psalm-pure
 */
function ForeachFunc(mixed $collection, mixed $lambdaExpr): Expr
{
    assertIsExprArg($collection);
    assertIsExprArg($lambdaExpr);

    return new Expr(['foreach' => wrap($lambdaExpr), 'collection' => wrap($collection)]);
}
