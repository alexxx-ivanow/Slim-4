<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Views\Twig;
use App\Controllers\Interfaces\InterfacesController;

//final class ErrorPageController implements InterfacesController
final class ErrorPageController extends BaseController
{
    public function display(): ResponseInterface {

        $container = $this->app->getContainer();
        $settings = $container->get('settings');

        if($this->config['code'] === 404) {
            $viewData = [
                'seo_title' => '404',
                'pagetitle' => 'Страница не найдена',
                'description' => 'Вы ввели неверный урл или адрес страницы устарел',
                'content' => '<p>Попробуйте набрать другие страницы</p>',
            ];
        } else {
            $viewData = [
                'seo_title' => 'Ошибка',
                'pagetitle' => 'Непредвиденная ошибка',
                'description' => 'Что-то пошло не так...',
            ];
            if($settings['displayErrorDetails']) {
                $viewData['content'] = $this->config['message'];
            }
        }

        $view = Twig::create($settings['template_path'], ['cache' => false]);
        return $view->render($this->response, 'pages/page.html.twig', $viewData);
    }
}