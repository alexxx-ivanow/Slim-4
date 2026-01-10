<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use \App\Action\HomeAction;
use \App\Action\ContactsAction;

use Psr\Container\ContainerInterface;
use App\Middleware\PermissionMiddleware;

return function (App $app) {

    $app->get('/contacts/', ContactsAction::class)->setName('contacts');

    $app->get('/', HomeAction::class)->setName('home')->add(PermissionMiddleware::class);;

    $app->post('/users/', UserCreateAction::class); // пока не реализован
};