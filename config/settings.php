<?php

// Необходимо установить 0 для рабочего режима
error_reporting(E_ALL);

// Необходимо установить '0' для рабочего режима
ini_set('display_errors', '1');

// Часовой пояс
date_default_timezone_set('Europe/Moscow');

// Настройки
$settings = [];

// Настройки пути
$settings['root'] = dirname(__DIR__);
$settings['template_path'] = dirname(__DIR__) . '/templates';
$settings['image_path'] = 'assets/img/';
$settings['cache_path_img'] = 'cache/image_resize/';

// Настройки посредников для обработки ошибок
$settings['error'] = [

    // Необходимо установить false для рабочего режима
    'display_error_details' => true,

    // Параметр передается в обработчик ошибок по умолчанию
    // Просмотр в сгенерированном выводе, включив настройку "displayErrorDetails".
    // Для консоли и модульных тестов мы также отключаем это
    'log_errors' => true,

    // Отображение подробностей ошибки в журнале ошибок
    'log_error_details' => true,
];

// Slim Settings
$settings['determineRouteBeforeAppMiddleware'] = false; // если нужно получить доступ к маршруту из middleware, то true
$settings['displayErrorDetails'] = true;

require __DIR__ . '/../env.php';
$settings['db'] = [
    'driver' => 'mysql',
    'host' => DB_HOST,
    'database' => DB_NAME,
    'username' => DB_LOGIN,
    'password' => DB_PASSWORD,
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => DB_PREFIX,
];

$settings['ErrorMiddleware'] = [
    'customErrorHandler' => false, // свой обработчик errorMiddleware
    'displayErrorDetails' => true, // выводить детали ошибки
    'logErrors' => false, // писать в лог
    'logErrorDetails' => false, // детали в лог
];

/**
* JWT настройки
* JWT_SECRET в env.php
*/
$settings['jwt'] = [
    'secret' => defined('JWT_SECRET') ? JWT_SECRET : 'change-me-in-env-min-32-chars!!',
    'algorithm' => 'HS256',
    'expire' => 3600,
    'refresh_expire' => 604800,
    'header' => 'Authorization',
    'header_prefix' => 'Bearer',
];

return $settings;