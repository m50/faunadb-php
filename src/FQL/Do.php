<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use Webmozart\Assert\Assert;

function DoFunc(mixed ...$args): Expr
{
    Assert::minCount($args, 1);
    assertAllIsExprArg($args);

    return new Expr(['do' => wrap($args)]);
}
