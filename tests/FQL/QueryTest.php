<?php

use FaunaDB\Client\Query as Q;
use FaunaDB\FQL;

test('can call FQL functions', function () {
    $expr = Q::Abort('test');
    $expr2 = FQL\Abort('test');

    expect($expr)->fql()->toBe($expr2->toFQL());
});

test('can call special case FQL functions', function () {
    $exprShortName = Q::If(true, 'hi');
    $exprFullName = Q::IfFunc(true, 'hi');
    $exprFunc = FQL\IfFunc(true, 'hi');

    expect($exprShortName)->fql()->toBe($exprFunc->toFQL());
    expect($exprFullName)->fql()->toBe($exprFunc->toFQL());
});

test('throws if FQL function does not exist', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('FQL Function \\FaunaDB\\FQL\\Blah does not exist.');

    Q::Blah();
});

test('fql query string', function () {
    $query = Q::Equals(Q::Query(Q::Lambda(fn ($a): int => 5)), Q::Query(Q::Lambda(fn ($a) => 5)));
    expect($query)->fql()->toBe('Equals(Query(Lambda("a", 5)), Query(Lambda("a", 5)))');
});
