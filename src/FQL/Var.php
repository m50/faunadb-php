<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

function VarFunc(string $varName): Expr
{
    return new Expr(['var' => wrap($varName)]);
}
