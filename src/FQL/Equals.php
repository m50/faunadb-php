<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use Webmozart\Assert\Assert;


function Equals(mixed ...$exprs): Expr
{
    Assert::minCount($exprs, 1);
    assertAllIsExprArg($exprs);

    return new Expr(['equals' => wrap(varargs($exprs))]);
}
