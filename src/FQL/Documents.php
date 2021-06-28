<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

function Documents(mixed $collection): Expr
{
    assertIsExprArg($collection);

    return new Expr(['documents' => wrap($collection)]);
}
