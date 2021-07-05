<?php

declare(strict_types=1);

namespace FaunaDB\Client;

use FaunaDB\Expr\Expr;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

use function function_exists;

/**
 * @method static Expr Abort(string $msg)
 * @method static Expr AccessProvider(mixed $expr)
 * @method static Expr Append(mixed $elements, mixed $collection)
 * @method static Expr At(DateTimeInterface|int $time, mixed $expr)
 * @method static Expr Call(mixed $ref, mixed ...$args)
 * @method static Expr Casefold(string $string, string $normalizer)
 * @method static Expr Concat(Collection|array $vars, ?string $separator = null)
 * @method static Expr Create(mixed $collectionRef, mixed $params)
 * @method static Expr Delete(mixed $ref)
 * @method static Expr DoFunc(mixed ...$args)
 * @method static Expr Documents(mixed $collection)
 * @method static Expr Drop(mixed $number, mixed $collection)
 * @method static Expr Equals(mixed ...$exprs)
 * @method static Expr Exists(mixed $ref, mixed $ts = null)
 * @method static Expr Filter(mixed $collection, mixed $lambdaExpr)
 * @method static Expr ForeachFunc(mixed $collection, mixed $lambdaExpr)
 * @method static Expr Get(mixed $ref, null|int|DateTimeInterface $ts = null)
 * @method static Expr IfFunc(mixed $condition, mixed $then, mixed $else = null)
 * @method static Expr Insert(mixed $ref, mixed $ts, mixed $action, mixed $params)
 * @method static Expr Is(string $type, mixed $expr)
 * @method static Expr IsEmpty(mixed $collection)
 * @method static Expr IsNonEmpty(mixed $collection)
 * @method static Expr IsNotEmpty(mixed $collection)
 * @method static Expr IsNumber(mixed $expr)
 * @method static Expr IsDouble(mixed $expr)
 * @method static Expr IsInteger(mixed $expr)
 * @method static Expr IsBoolean(mixed $expr)
 * @method static Expr IsBool(mixed $expr)
 * @method static Expr IsNull(mixed $expr)
 * @method static Expr IsBytes(mixed $expr)
 * @method static Expr IsTimestamp(mixed $expr)
 * @method static Expr IsDate(mixed $expr)
 * @method static Expr IsString(mixed $expr)
 * @method static Expr IsArray(mixed $expr)
 * @method static Expr IsObject(mixed $expr)
 * @method static Expr IsRef(mixed $expr)
 * @method static Expr IsSetFunc(mixed $expr)
 * @method static Expr IsDoc(mixed $expr)
 * @method static Expr IsDocument(mixed $expr)
 * @method static Expr IsLambda(mixed $expr)
 * @method static Expr IsCollection(mixed $expr)
 * @method static Expr IsDatabase(mixed $expr)
 * @method static Expr IsIndex(mixed $expr)
 * @method static Expr IsFunction(mixed $expr)
 * @method static Expr IsKey(mixed $expr)
 * @method static Expr IsToken(mixed $expr)
 * @method static Expr IsCredentials(mixed $expr)
 * @method static Expr IsRole(mixed $expr)
 * @method static Expr KeyFromSecret(mixed $secret)
 * @method static Expr Lambda(callable|Expr $func, null|array|Arrayable $varNames = null)
 * @method static Expr Let(Collection|array $vars, mixed $expr)
 * @method static Expr Map(mixed $collection, mixed $lambdaExpr)
 * @method static Expr Prepend(mixed $elements, mixed $collection)
 * @method static Expr Query(mixed $lambda)
 * @method static Expr Reduce(mixed $lambda, mixed $initial, mixed $collection)
 * @method static Expr Ref(mixed $ref, mixed $id = null)
 * @method static Expr Remove(mixed $ref, mixed $ts, mixed $action)
 * @method static Expr Replace(mixed $ref, mixed $params)
 * @method static Expr Reverse(mixed $expr)
 * @method static Expr Take(mixed $number, mixed $collection)
 * @method static Expr Update(mixed $ref, mixed $params)
 * @method static Expr VarFunc(string $varName)
 */
final class Query
{
    private function __construct()
    {
        // This is a static class.
    }

    public static function __callStatic(string $name, array $arguments): Expr
    {
        $namespace = '\\FaunaDB\\FQL\\';
        $func = $namespace . $name . 'Func';

        // If real param doesn't end with `Func`, then use real name
        if (! function_exists($func)) {
            $func = "{$namespace}{$name}";
        }

        if (! function_exists($func)) {
            throw new InvalidArgumentException("FQL Function {$func} does not exist.");
        }

        $result = $func(...$arguments);
        Assert::isInstanceOf($result, Expr::class);

        return $result;
    }
}
