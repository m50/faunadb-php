<?php

$files = scandir(__DIR__ . '/FQL/');

include_once(__DIR__ . '/FQL/helpers.php');
include_once(__DIR__ . '/FQL/Lambda.php');

foreach ($files as $file) {
    if (strpos($file, '.php') === false) {
        continue;
    }
    include_once(__DIR__ . "/FQL/{$file}");
}
