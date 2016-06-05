<?php

$autoloader = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloader)) {
    echo 'Missing autoloader file. Run `composer install` to install composer dependencies.';
    exit(1);
}
require $autoloader;

use Smatyas\Mfw\Application;
use Smatyas\Mfw\Router\Route;

$app = new Application();

$route = new Route();
$route->setPath('/test');
$route->setController('Smatyas\\MfwApp\\Controller\\DefaultController');
$app->getRouter()->addRoute($route);

$app->run();
