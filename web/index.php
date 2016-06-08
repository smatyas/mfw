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
    'orm.config' => [
        'type' => 'mfw',
        'host' => 'db',
        'database' => 'mfw',
        'username' => 'mfw',
        'password' => 'mfw',
        'mapping' => [
            'user' => 'Smatyas\\MfwApp\\Entity\\User',
        ],
    ],
    'security.config' => [
        'login_path' => '/login',
        'paths' => [
            '/page1' => ['PAGE_1'],
            '/page2' => ['PAGE_2'],
        ],
    ],
    'error_handler.config' => [
        'type' => 'email',
        'to' => 'ops@example.com',
    ],
];
$app = new Application($applicationConfig);
$app->addRoute('/', 'Smatyas\\MfwApp\\Controller\\DefaultController');
$app->addRoute('/login', 'Smatyas\\MfwApp\\Controller\\LoginController');
$app->addRoute('/login/captcha', 'Smatyas\\MfwApp\\Controller\\LoginController', 'captcha');
$app->addRoute('/login/check', 'Smatyas\\MfwApp\\Controller\\LoginController', 'check', 'POST');
$app->addRoute('/logout', 'Smatyas\\MfwApp\\Controller\\LoginController', 'logout');
$app->addRoute('/page1', 'Smatyas\\MfwApp\\Controller\\SecuredPageController', 'page1');
$app->addRoute('/page2', 'Smatyas\\MfwApp\\Controller\\SecuredPageController', 'page2');
$app->run();
