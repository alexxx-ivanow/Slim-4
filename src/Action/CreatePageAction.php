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
use App\DTO\CreatePageDTO;
use Slim\Psr7\Response;

final class CreatePageAction
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
        /*$routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();*/

        $params = [];
        if($request->getMethod() === 'POST') {
            // получаем данные формы
            try {
                //$paramsPage = new CreatePageDTO((array)$request->getParsedBody());
                $params = (array)$request->getParsedBody();
            } catch (\Exception $e) {
                $this->container->get('Flash')->addMessageNow(
                    'error',
                    $e->getMessage()
                );
            }

            try {
                // валидация полей
                if($request->getAttribute('has_errors')){
                    $errors = $request->getAttribute('errors');
                    $formatErrors = [];
                    foreach($errors as $key => $error) {
                        $formatErrors[$key] = implode(', ', $error);
                    }
                    $params['formValidate'] = $formatErrors;
                } else {
                    // сохраняем новую страницу
                    //$page = new Page($paramsPage->getPage());
                    $page = new Page($params);
                    if($page->save()) {
                        $this->container->get('Flash')->addMessage(
                            'global',
                            'Новая страница создана'
                        );
                        return $response->withStatus(301)->withHeader('Location', $request->getUri()->getPath());
                    } else {
                        $this->container->get('Flash')->addMessage(
                            'error',
                            'Ошибка при создании новой страницы'
                        );
                    }
                }
            } catch (\Exception $e) {
                $this->container->get('Flash')->addMessageNow(
                    'error',
                    $e->getMessage()
                );
            }
        }

        // ловим оповещения об отправке формы
        $params['error_messages'] = false;
        $messages = $this->container->get('Flash')->getMessages();
        if(is_array($messages) && array_key_exists('global', $messages)) {
            $params['messages'] = $messages['global'];
        } elseif(is_array($messages) && array_key_exists('error', $messages)) {
            $params['messages'] = $messages['error'];
            $params['error_messages'] = true;
        } else {
            $params['messages'] = [];
        }

        // выводим форму
        $view = Twig::fromRequest($request);
        return $view->render($response, 'pages/createPage.html.twig', ['params' => $params]);
    }
}