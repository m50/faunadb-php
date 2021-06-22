<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use FaunaDB\Result\Collection;
use function is_array;
use function is_callable;

/**
 * @psalm-param Collection<string,ExprArg>|array<string,ExprArg> $vars
 * @psalm-return Expr
 * @psalm-pure
 */
function Concat(Collection|array $vars, ?string $separator = null): Expr
{
    $vars = Collection::from($vars);

    return new Expr(params(['concat' => wrap($vars->toArray())], ['separator' => wrap($separator)]));
}
