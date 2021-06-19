<?php

use FaunaDB\Result\Collection;

test('can map over collection', function () {
    $col = new Collection(['test', 'test2', 'test3']);
    $col2 = $col->map(fn ($o, $k, $i) => "{$o}-{$k}-{$i}");
    expect($col2[0])->toBe('test-0-0');
    expect($col2[1])->toBe('test2-1-1');
    expect($col2[2])->toBe('test3-2-2');
});

test('can each a collection', function () {
    $col = new Collection(['test', 'test2']);
    $i = 0;
    $col->each(function ($o, $k) use (&$i) {
        $i = $k;
        expect($o)->toBeString();
    });
    expect($i)->toBe(1);
});

test('can iterate over collection', function () {
    $col = new Collection(['k' => 'test', 'j' => 'test']);
    foreach ($col as $k => $v) {
        expect($k)->toBeString();
        expect($v)->toBeString()->toBe('test');
    }
});
