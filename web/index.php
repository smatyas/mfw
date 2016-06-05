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
$app->setAppBasePath(__DIR__ . '/../src');

$route = new Route();
$route->setPath('/');
$route->setController('Smatyas\\MfwApp\\Controller\\DefaultController');
$app->getRouter()->addRoute($route);

$route = new Route();
$route->setPath('/login');
$route->setController('Smatyas\\MfwApp\\Controller\\LoginController');
$app->getRouter()->addRoute($route);

$route = new Route();
$route->setPath('/login/captcha');
$route->setController('Smatyas\\MfwApp\\Controller\\LoginController');
$route->setControllerAction('captcha');
$app->getRouter()->addRoute($route);

$route = new Route();
$route->setPath('/login/check');
$route->setMethod('POST');
$route->setController('Smatyas\\MfwApp\\Controller\\LoginController');
$route->setControllerAction('check');
$app->getRouter()->addRoute($route);

$app->run();
