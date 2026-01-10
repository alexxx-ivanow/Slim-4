<?php

namespace App\Action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

use Illuminate\Database\Capsule\Manager;
use App\Models\Page;

use \Gumlet\ImageResize;

final class ContactsAction
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

        $settings = $this->container->get('settings');

        $page = Page::where('alias', 'contacts')->get();

        $viewData = $page[0]->toArray();
        if($viewData['image'] && file_exists($settings['root'] . '/public/' . $settings['image_path'] . $viewData['image'])) {
            $imgPath = $settings['cache_path_img'] . 'image_index_top.jpg';
            $imgRoot = $settings['root'] . '/public/' . $imgPath;

            //SS($settings);

            if(!file_exists($imgRoot)) {

                $image = new ImageResize($settings['image_path'] . $viewData['image']);
                $image->crop(200, 200, true, ImageResize::CROPTOP);
                $image->save($imgPath);
                $viewData['image'] = $imgPath;
            } else {
                $viewData['image'] = $imgPath;
            }

        } else {
            $viewData['image'] = '/' . $settings['image_path'] . 'no_photo.png';
        }

        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/page.html.twig', $viewData);
    }
}