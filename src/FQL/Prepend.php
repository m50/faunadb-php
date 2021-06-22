<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @param mixed $elements An expression resulting in a collection of elements to prepend to the given collection.
 * @param mixed $collection An expression resulting in a collection.
 * @psalm-param ExprArg $elements
 * @psalm-param ExprArg $collection
 * @psalm-pure
 */
function Prepend(mixed $elements, mixed $collection): Expr
{
    assertIsExprArg($elements);
    assertIsExprArg($collection);

    return new Expr(['prepend' => wrap($elements), 'collection' => wrap($collection)]);
}
