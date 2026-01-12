<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use \App\Action\PageAction;
use \App\Action\CreatePageAction;
//use Respect\Validation\Validator as v;

use Psr\Container\ContainerInterface;
//use App\Middleware\PermissionMiddleware;
use App\Lib\Form\FormUtils;

return function (App $app) {

    $app->get('/contacts/', PageAction::class)->setName('contacts');

    $app->get('/', PageAction::class)->setName('index');

    // валидация полей формы
    $validators = FormUtils::validatorsForm();
    $translatorForm = function($message) { // переводы
        return FormUtils::translatorForm($message);
    };
    $app->add(new \Inok\Slim\Validation\Validation($validators, $translatorForm));

    $app->map(['GET', 'POST'], '/admin/page/create/', CreatePageAction::class)->setName('page.create');

    //$app->post('/users/', UserCreateAction::class); // пока не реализован
    //->add(PermissionMiddleware::class)
};