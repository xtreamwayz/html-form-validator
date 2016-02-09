<?php
include __DIR__ . '/../vendor/autoload.php';

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug'        => true,
    'includePaths' => [__DIR__ . '/../src'],
]);
