<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use \App\Action\PageAction;
use \App\Action\LoginAction;
use \App\Action\RefreshAction;
use \App\Action\ProfileAction;
use \App\Action\CreatePageAction;
use App\Action\LoginPageAction;
//use Respect\Validation\Validator as v;

use Psr\Container\ContainerInterface;
use App\Middleware\PermissionMiddleware;
use App\Lib\Form\FormUtils;

return function (App $app) {

    // страница логина (UI)
    $app->get('/login/', LoginPageAction::class)->setName('login.page');

    $app->get('/contacts/', PageAction::class)->setName('contacts');
    $app->get('/', PageAction::class)->setName('index');

    // валидация полей формы
    $validators = FormUtils::validatorsForm();
    $translatorForm = function($message) { // переводы
        return FormUtils::translatorForm($message);
    };
    $app->add(new \Inok\Slim\Validation\Validation($validators, $translatorForm));

    $app->map(['GET', 'POST'], '/admin/page/create/', CreatePageAction::class)->setName('page.create');

    // Публичные API-маршруты (без токена)
    $app->post('/auth/login/',   LoginAction::class);
    $app->post('/auth/refresh/', RefreshAction::class);

    // Защищённые API-маршруты (требуют JWT)
    $app->group('/api', function ($group) {

        // Профиль текущего пользователя
        $group->get('/profile/', ProfileAction::class);

        // Сюда добавляйте другие защищённые маршруты:
        // $group->get('/users',  UserListAction::class);
        // $group->delete('/users/{id}', UserDeleteAction::class);

    })->add(JwtMiddleware::class);  // ← PHP-DI автоматически создаст экземпляр

};