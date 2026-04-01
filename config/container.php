<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Flash\Messages;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Illuminate\Database\Capsule\Manager;

use DebugBar\StandardDebugBar;

use \Gumlet\ImageResize;
use App\Services\JwtService;

return [
    // настройки приложения
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    // объект приложения из контейнера
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        return AppFactory::create();
    },

    // Flash-сообщения
    'Flash' => function () {
        return new Messages();
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },

    /*ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get('settings')['error'];

        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool) $settings['display_error_details'],
            (bool) $settings['log_errors'],
            (bool) $settings['log_error_details']
        );
    },*/

    // Eloquent ORM
    Manager::class => function (ContainerInterface $container) {
        $capsule = new Manager;
        $capsule->addConnection($container->get('settings')['db']);

        //$capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    },

    // JWT-сервис: читает настройки из settings['jwt']
    JwtService::class => function (ContainerInterface $container) {
        $settings = $container->get('settings');
        return new JwtService($settings['jwt']['secret']);
    },


];