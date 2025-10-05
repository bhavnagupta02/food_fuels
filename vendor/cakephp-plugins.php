<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Bake' => $baseDir . '/vendor/cakephp/bake/',

    ]
];
