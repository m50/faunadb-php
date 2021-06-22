<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;

/**
 * A more generic `Is` expression, that allows dynamic type checking.
 *
 * @param string $type Must be the other half of the function name.
 *  Examples:
 *   - `FQL\Is('empty', FQL\Collection(...)); => IsEmpty(Collection(...))`
 *   - `FQL\Is('null', null); => IsNull(null)`
 * @param mixed $expr
 * @psalm-param ExprArg $collection
 * @return Expr
 * @psalm-pure
 */
function Is(string $type, mixed $expr): Expr
{
    $type = ucfirst(strtolower($type));
    if ($type === 'Set') {
        $type = 'SetFunc';
    }
    $func = __NAMESPACE__ . "\\Is{$type}";

    return call_user_func($func, $expr);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#collections).
 * @param mixed $collection An expression resulting in a collection.
 * @psalm-param ExprArg $collection
 * @psalm-pure
 */
function IsEmpty(mixed $collection): Expr
{
    assertIsExprArg($collection);

    return new Expr(['is_empty' => wrap($collection)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#collections).
 * @param mixed $collection An expression resulting in a collection.
 * @psalm-param ExprArg $collection
 * @psalm-pure
 */
function IsNonEmpty(mixed $collection): Expr
{
    assertIsExprArg($collection);

    return new Expr(['is_nonempty' => wrap($collection)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#collections).
 * Alias of IsNonEmpty to be more PHP familiar.
 * @param mixed $collection An expression resulting in a collection.
 * @psalm-param ExprArg $collection
 * @psalm-pure
 */
function IsNotEmpty(mixed $collection): Expr
{
    assertIsExprArg($collection);

    return new Expr(['is_nonempty' => wrap($collection)]);
}

/**
 * Check if the expression is a number.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsNumber](https://docs.fauna.com/fauna/current/api/fql/functions/isnumber)
 */
function IsNumber(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_number' => wrap($expr)]);
}

/**
 * Check if the expression is a double.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsDouble](https://docs.fauna.com/fauna/current/api/fql/functions/isdouble)
 */
function IsDouble(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_double' => wrap($expr)]);
}

/**
 * Check if the expression is an integer.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsInteger](https://docs.fauna.com/fauna/current/api/fql/functions/isinteger)
 */
function IsInteger(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_integer' => wrap($expr)]);
}

/**
 * Check if the expression is a boolean.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsBoolean](https://docs.fauna.com/fauna/current/api/fql/functions/IsBoolean)
 */
function IsBoolean(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_boolean' => wrap($expr)]);
}

/**
 * Check if the expression is a boolean.
 * Alias of IsBoolean to be more PHP familiar.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsBoolean](https://docs.fauna.com/fauna/current/api/fql/functions/IsBoolean)
 */
function IsBool(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_boolean' => wrap($expr)]);
}

/**
 * Check if the expression is a null.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsNull](https://docs.fauna.com/fauna/current/api/fql/functions/IsNull)
 */
function IsNull(mixed $expr): Expr
{
    assertIsExprArg($expr, true);

    return new Expr(['is_null' => wrap($expr)]);
}

/**
 * Check if the expression is a byte array.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsBytes](https://docs.fauna.com/fauna/current/api/fql/functions/IsBytes)
 */
function IsBytes(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_bytes' => wrap($expr)]);
}

/**
 * Check if the expression is a timestamp.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsTimestamp](https://docs.fauna.com/fauna/current/api/fql/functions/IsTimestamp)
 */
function IsTimestamp(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_timestamp' => wrap($expr)]);
}

/**
 * Check if the expression is a date.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsDate](https://docs.fauna.com/fauna/current/api/fql/functions/IsDate)
 */
function IsDate(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_date' => wrap($expr)]);
}

/**
 * Check if the expression is a string.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsString](https://docs.fauna.com/fauna/current/api/fql/functions/IsString)
 */
function IsString(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_string' => wrap($expr)]);
}

/**
 * Check if the expression is an array.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsArray](https://docs.fauna.com/fauna/current/api/fql/functions/IsArray)
 */
function IsArray(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_array' => wrap($expr)]);
}

/**
 * Check if the expression is an Object.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsObject](https://docs.fauna.com/fauna/current/api/fql/functions/IsObject)
 */
function IsObject(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_object' => wrap($expr)]);
}

/**
 * Check if the expression is a ref.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsRef](https://docs.fauna.com/fauna/current/api/fql/functions/IsRef)
 */
function IsRef(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_ref' => wrap($expr)]);
}

/**
 * Check if the expression is a set.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsSet](https://docs.fauna.com/fauna/current/api/fql/functions/IsSet)
 */
function IsSetFunc(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_set' => wrap($expr)]);
}

/**
 * Check if the expression is a document.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsDoc](https://docs.fauna.com/fauna/current/api/fql/functions/IsDoc)
 */
function IsDoc(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_doc' => wrap($expr)]);
}

/**
 * Check if the expression is a document.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsDoc](https://docs.fauna.com/fauna/current/api/fql/functions/IsDoc)
 */
function IsDocument(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_doc' => wrap($expr)]);
}

/**
 * Check if the expression is a lambda.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsLambda](https://docs.fauna.com/fauna/current/api/fql/functions/IsLambda)
 */
function IsLambda(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_lambda' => wrap($expr)]);
}

/**
 * Check if the expression is a collection.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsCollection](https://docs.fauna.com/fauna/current/api/fql/functions/IsCollection)
 */
function IsCollection(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_collection' => wrap($expr)]);
}

/**
 * Check if the expression is a Database.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsDatabase](https://docs.fauna.com/fauna/current/api/fql/functions/IsDatabase)
 */
function IsDatabase(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_database' => wrap($expr)]);
}

/**
 * Check if the expression is an Index.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsIndex](https://docs.fauna.com/fauna/current/api/fql/functions/IsIndex)
 */
function IsIndex(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_index' => wrap($expr)]);
}

/**
 * Check if the expression is a function.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsFunction](https://docs.fauna.com/fauna/current/api/fql/functions/IsFunction)
 */
function IsFunction(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_function' => wrap($expr)]);
}

/**
 * Check if the expression is a key.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsKey](https://docs.fauna.com/fauna/current/api/fql/functions/IsKey)
 */
function IsKey(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_key' => wrap($expr)]);
}

/**
 * Check if the expression is a token.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsToken](https://docs.fauna.com/fauna/current/api/fql/functions/IsToken)
 */
function IsToken(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_token' => wrap($expr)]);
}

/**
 * Check if the expression is credentials.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsCredentials](https://docs.fauna.com/fauna/current/api/fql/functions/IsCredentials)
 */
function IsCredentials(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_credentials' => wrap($expr)]);
}

/**
 * Check if the expression is a role.
 * @param mixed $expr The expression to check
 * @psalm-param ExprArg $expr
 * @psalm-pure
 * @see [IsRole](https://docs.fauna.com/fauna/current/api/fql/functions/IsRole)
 */
function IsRole(mixed $expr): Expr
{
    assertIsExprArg($expr);

    return new Expr(['is_role' => wrap($expr)]);
}
