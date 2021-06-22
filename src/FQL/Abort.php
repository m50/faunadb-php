<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

function Abort(string $msg): Expr
{
    return new Expr(['abort' => wrap($msg)]);
}
