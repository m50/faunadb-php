#!/usr/bin/env php
<?php

$folder = dirname(__DIR__) . '/src/FQL';

$files = scandir($folder);

$out = ["/**"];

foreach ($files as $file) {
    if (!str_contains($file, '.php')) {
        continue;
    }
    $fileContents = file_get_contents($folder . DIRECTORY_SEPARATOR . $file);
    $lines = explode("\n", $fileContents);
    foreach ($lines as $line) {
        if (preg_match('/^function ([A-Z].*): Expr$/', $line, $matches)) {
            [, $func] = $matches;
            $out[] = " * @method static Expr {$func}";
        }
    }
}

$out[] = ' */';

$queryFile = dirname(__DIR__) . '/src/Client/Query.php';

$lines = explode("\n", file_get_contents($queryFile));

$commentLineNo = 0;
$classLineNo = 0;
foreach ($lines as $lineNo => $line) {
    if ($line === '/**') {
        $commentLineNo = $lineNo;
    }
    if (str_contains($line, 'final class Query')) {
        $classLineNo = $lineNo;
    }
}
$lines = array_merge(
    array_slice($lines, 0, $commentLineNo),
    $out,
    array_slice($lines, $classLineNo),
);

file_put_contents($queryFile, implode("\n", $lines));
