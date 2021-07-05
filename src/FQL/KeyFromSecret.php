<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

function KeyFromSecret(mixed $secret): Expr
{
    assertIsExprArg($secret);

    return new Expr(['key_from_secret' => wrap($secret)]);
}
