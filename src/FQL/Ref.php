<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * @psalm-return Expr
 */
function Ref(mixed $ref, mixed $id = null): Expr
{
    assertIsExprArg($ref);
    assertIsExprArg($id, true);

    if ($id !== null) {
        return new Expr(['ref' => $ref, 'id' => $id]);
    }

    return new Expr(['@ref' => $ref]);
}
