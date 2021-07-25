<?php

declare(strict_types=1);

namespace FaunaDB\FQL;

use FaunaDB\Expr\Expr;
use Webmozart\Assert\Assert;

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param mixed $collectionRef The Ref (usually a CollectionRef) to create.
 * @param array $params An object representing the parameters of the document.
 * @return Expr
 */
function Create(mixed $collectionRef, array $params): Expr
{
    assertIsExprArg($collectionRef);

    return new Expr(['create' => wrap($collectionRef), 'params' => wrap($params)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create a class.
 *     - name (required): the name of the class to create
 * @psalm-param array{name:string|Expr} $params
 * @phpstan-param array{name:string|Expr} $params
 * @return Expr
 *
 * @deprecated use CreateCollection instead
 */
function CreateClass(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_class' => wrap($params)]);
}


/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create a collection.
 *     - name (required): the name of the collection to create
 * @psalm-param array{name:string|Expr} $params
 * @phpstan-param array{name:string|Expr} $params
 * @return Expr
 */
function CreateCollection(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_collection' => wrap($params)]);
}


/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create a database.
 *     - name (required): the name of the database to create
 * @psalm-param array{name:string|Expr} $params
 * @phpstan-param array{name:string|Expr} $params
 * @return Expr
 */
function CreateDatabase(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_database' => wrap($params)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create an index.
 *     - name (required): the name of the index to create
 *     - source: One or more source objects describing source collections and (optional) field bindings.
 *     - terms: An array of term objects describing the fields to be indexed. Optional
 *     - values: An array of value objects describing the fields to be covered. Optional
 *     - unique: If true, maintains a uniqueness constraint on combined terms and values. Optional
 *     - partitions: The number of sub-partitions within each term. Optional
 * @psalm-param array{name:string|Expr,source:mixed,terms:array,values:array,unique:bool|Expr,partitions:int|Expr} $params
 * @phpstan-param array{name:string|Expr,source:mixed,terms:array,values:array,unique:bool|Expr,partitions:int|Expr} $params
 * @return Expr
 */
function CreateIndex(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_index' => wrap($params)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create a new key
 *     - database: Ref of the database the key will be scoped to. Optional.
 *     - role: The role of the new key
 * @psalm-param array{database:mixed,role:mixed} $params
 * @phpstan-param array{database:mixed,role:mixed} $params
 * @return Expr
 */
function CreateKey(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_key' => wrap($params)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create a new user defined function.
 *     - name: The name of the function
 *     - body: A lambda function (escaped with `query`).
 * @psalm-param array{name:string|Expr,body:Expr|callable} $params
 * @phpstan-param array{name:string|Expr,body:Expr|callable} $params
 * @return Expr
 */
function CreateFunction(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_function' => wrap($params)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create a new role.
 *     - name: The name of the role
 *     - privileges: An array of privileges
 *     - membership: An array of membership bindings
 * @psalm-param array{name:string|Expr,privileges:array,membership:array} $params
 * @phpstan-param array{name:string|Expr,privileges:array,membership:array} $params
 * @return Expr
 */
function CreateRole(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_role' => wrap($params)]);
}

/**
 * See the [docs](https://app.fauna.com/documentation/reference/queryapi#write-functions).
 *
 * @param array $params
 *   An object of parameters used to create a new access provider.
 *     - name: A valid schema name
 *     - issuer: A unique string
 *     - jwks_uri: A valid HTTPS URI
 *     - roles: An array of role/predicate pairs where the predicate returns a boolean.
 *                   The array can also contain Role references.
 * @psalm-param array{name:string|Expr,issuer:string|Expr,jwks_uri:string|Expr,roles:array} $params
 * @phpstan-param array{name:string|Expr,issuer:string|Expr,jwks_uri:string|Expr,roles:array} $params
 * @return Expr
 */
function CreateAccessProvider(array $params): Expr
{
    Assert::isMap($params);

    return new Expr(['create_access_provider' => wrap($params)]);
}
