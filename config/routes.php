<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use \App\Action\PageAction;

use Psr\Container\ContainerInterface;
use App\Middleware\PermissionMiddleware;

return function (App $app) {

    $app->get('/contacts/', PageAction::class)->setName('contacts');

    $app
        ->get('/', PageAction::class)->setName('index');
        //->add(PermissionMiddleware::class)

    //$app->post('/users/', UserCreateAction::class); // пока не реализован
};