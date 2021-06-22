<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @param mixed $number An expression resulting in the number of elements to drop from the collection.
 * @param mixed $collection An expression resulting in a collection.
 * @psalm-param ExprArg $number
 * @psalm-param ExprArg $collection
 * @psalm-pure
 */
function Drop(mixed $number, mixed $collection): Expr
{
    assertIsExprArg($number);
    assertIsExprArg($collection);

    return new Expr(['drop' => wrap($number), 'collection' => wrap($collection)]);
}
