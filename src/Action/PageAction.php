<?php

namespace App\Action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager;
use App\Models\Page;
use \Gumlet\ImageResize;
use Slim\Routing\RouteContext;

final class PageAction
{
    private $container;

    public function __construct(
        Manager $connection,
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        // получаем текущий роутер
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $routeName = $route->getName();

        // получаем настройки приложения
        $settings = $this->container->get('settings');

        // выборка из БД полей страницы
        $page = Page::where('alias', $routeName)->get();
        $viewData = $page[0]->toArray();

        // подготовка обрезки картинки
        if($viewData['image'] && file_exists($settings['root'] . '/public/' . $settings['image_path'] . $viewData['image'])) {
            $imgPath = $settings['cache_path_img'] . 'image_' . $routeName . '_top.jpg';
            $imgRoot = $settings['root'] . '/public/' . $imgPath;

            if(!file_exists($imgRoot)) {
                $image = new ImageResize($settings['image_path'] . $viewData['image']);
                $image->crop(1200, 285, true, ImageResize::CROPCENTER);
                $image->save($imgPath);
                $viewData['image'] = $imgPath;
            } else {
                $viewData['image'] = $imgPath;
            }

        }
        // получаем uri роутера
        $uri = $request->getUri();
        $viewData['uri'] = $uri->getPath();

        //SS($viewData);

        // twig-шаблонизатор
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/page.html.twig', $viewData);
    }
}