<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use DateTimeInterface;
use FaunaDB\Expr\Expr;

function Get(mixed $ref, null|int|DateTimeInterface $ts = null): Expr
{
    assertIsExprArg($ref);
    if ($ts instanceof DateTimeInterface) {
        $ts = $ts->getTimestamp();
    }

    return new Expr(params(['get' => wrap($ref)], ['ts' => $ts]));
}
