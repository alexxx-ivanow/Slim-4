<?
namespace App\Action;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LoginPageAction
{
    //private Twig $view;

    //private $container;

    public function __construct(
        //Manager $connection,
        //ContainerInterface $container
    ) {
        //$this->container = $container;
    }

    /*public function __construct(Twig $view)
    {
        $this->view = $view;
    }*/

    public function __invoke(Request $request, Response $response): Response
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'auth/login.html.twig');
    }
}