<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use App\Controllers\ErrorPageController;

return function (App $app) {
    // Парсим json, данные формы и xml
    $app->addBodyParsingMiddleware();

    // Добавляем встроенных посредников маршрутизации Slim
    $app->addRoutingMiddleware();

    // кастомный обработчик ошибок
    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
    ) use ($app) {
        $response = $app->getResponseFactory()->createResponse();

        $config['code'] = $exception->getCode();
        $config['message'] = $exception->__toString();

        if($exception->getCode() === 404) {
            $response = $response->withStatus(404);
        }

        $ErrorPageController = new ErrorPageController($app, $response, $config);
        $ErrorPageController->display();

        return $response;
    };

    /** Отлавливаем исключения и ошибки */
    // кастомный обработчик ошибок
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

    // стандартный обработчик ошибок в container.php (закомментирован)
    //$errorMiddleware = $app->add(ErrorMiddleware::class);
};