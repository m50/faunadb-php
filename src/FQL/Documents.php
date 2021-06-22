<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @psalm-param ExprArg $collection
 * @psalm-pure
 */
function Documents(mixed $collection): Expr
{
    assertIsExprArg($collection);
    
    return new Expr(['documents' => wrap($collection)]);
}
