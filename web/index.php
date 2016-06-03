<?php

$autoloader = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloader)) {
    echo 'Missing autoloader file. Run `composer install` to install composer dependencies.';
    exit(1);
}
require $autoloader;

use Smatyas\Mfw\Application;

$app = new Application();
$app->run();
