<?php

use FaunaDB\FQL;

test('as fql', function () {
    expect('hi')->fql()->toBe('"hi"');
    expect(1)->fql()->toBe('1');
    expect(true)->fql()->toBe('true');
    expect(false)->fql()->toBe('false');
    expect(null)->fql()->toBe('null');
    expect(fn ($a) => FQL\IsBool($a))->fql()->toBe('Lambda("a", IsBoolean(Var("a")))');
});

test('FQL Is() function', function () {
    $expr = FQL\Is('null', FQL\VarFunc('blah'));
    expect($expr)->fql()->toBe('IsNull(Var("blah"))');
});

test('fql: Equals, Query, Lambda', function () {
    $query = FQL\Equals(FQL\Query(FQL\Lambda(fn ($a): int => 5)), FQL\Query(FQL\Lambda(fn ($a) => 5)));
    expect($query)->fql()->toBe('Equals(Query(Lambda("a", 5)), Query(Lambda("a", 5)))');
});

test('fql: Map, Lambda, Concat, Var', function () {
    $query = FQL\Map(['Hen '], fn ($name): string => FQL\Concat([$name, 'Wen']));
    expect($query)->fql()->toBe('Map(["Hen "], Lambda("name", Concat([Var("name"), "Wen"])))');

    $query = FQL\Map([['Hen', 'Wen']], FQL\Lambda(FQL\VarFunc('name'), ['name', '_']));
    expect($query)->fql()->toBe('Map([["Hen", "Wen"]], Lambda(["name", "_"], Var("name")))');
});
