<?php

use DI\ContainerBuilder;
use Slim\App;

use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

require_once __DIR__ . '/../vendor/autoload.php';

// Подключаем файл с утилитами
require __DIR__ . '/functions.php';

$containerBuilder = new ContainerBuilder();

// Устанавливаем настройки
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

// Создаём экземпляр контейнера PHP-DI
$container = $containerBuilder->build();

// Создаём экземпляр App
$app = $container->get(App::class);

$settings = $container->get('settings');

// Create Twig
$twig = Twig::create($settings['template_path'], ['cache' => false]);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

// Регистрируем маршруты
(require __DIR__ . '/routes.php')($app);

// Регистрируем посредники
(require __DIR__ . '/middleware.php')($app);

return $app;