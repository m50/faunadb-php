<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param mixed $collectionRef The Ref (usually a CollectionRef) to create.
 * @param mixed $params An object representing the parameters of the document.
 * @return Expr
 */
function Create(mixed $collectionRef, mixed $params): Expr
{
    assertIsExprArg($collectionRef);
    assertIsExprArg($params);

    return new Expr(['create' => wrap($collectionRef), 'params' => wrap($params)]);
}
