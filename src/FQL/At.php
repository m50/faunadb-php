<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use DateTimeInterface;
use FaunaDB\Expr\Expr;

function At(DateTimeInterface|int $time, mixed $expr): Expr
{
    assertIsExprArg($expr);

    if ($time instanceof DateTimeInterface) {
        $time = $time->getTimestamp();
    }

    return new Expr([
        'at' => wrap($time),
        'expr' => wrap($expr),
    ]);
}
