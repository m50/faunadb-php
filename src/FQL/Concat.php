<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use FaunaDB\Result\Collection;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#string-functions).
 *
 * @param Collection|array $vars A list of strings to concatenate.
 * @param string|null $separator The separator to use between each string.
 * @psalm-param Collection<string,mixed>|array<string,mixed> $vars
 * @psalm-return Expr
 */
function Concat(Collection|array $vars, ?string $separator = null): Expr
{
    $vars = Collection::from($vars);

    return new Expr(params(
        ['concat' => wrap($vars->toArray())],
        ['separator' => wrap($separator)]
    ));
}
