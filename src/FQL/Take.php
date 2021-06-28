<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @param mixed $number An expression resulting in the number of elements to take from the collection.
 * @param mixed $collection An expression resulting in a collection.
 */
function Take(mixed $number, mixed $collection): Expr
{
    assertIsExprArg($number);
    assertIsExprArg($collection);

    return new Expr(['take' => wrap($number), 'collection' => wrap($collection)]);
}
