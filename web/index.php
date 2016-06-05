<?php

$autoloader = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloader)) {
    echo 'Missing autoloader file. Run `composer install` to install composer dependencies.';
    exit(1);
}
require $autoloader;

use Smatyas\Mfw\Application;

$applicationConfig = [
    'app_base_path' => __DIR__ . '/../src',
];
$app = new Application($applicationConfig);
$app->addRoute('/', 'Smatyas\\MfwApp\\Controller\\DefaultController');
$app->addRoute('/login', 'Smatyas\\MfwApp\\Controller\\LoginController');
$app->addRoute('/login/captcha', 'Smatyas\\MfwApp\\Controller\\LoginController', 'captcha');
$app->addRoute('/login/check', 'Smatyas\\MfwApp\\Controller\\LoginController', 'check', 'POST');
$app->run();
