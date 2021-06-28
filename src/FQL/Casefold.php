<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#string-functions).
 *
 * @param string $string - The string to casefold.
 * @param string $normalizer - The algorithm to use. One of: NFKCCaseFold, NFC, NFD, NFKC, NFKD.
 * @psalm-param 'NFKCCaseFold'|'NFC'|'NFD'|'NFKC'|'NKFD' $normalizer
 * @psalm-return Expr
 */
function Casefold(string $string, string $normalizer): Expr
{
    return new Expr(params(['casefold' => wrap($string)], ['normalizer' => wrap($normalizer)]));
}
