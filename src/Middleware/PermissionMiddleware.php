<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

use Slim\Psr7\Response;

class PermissionMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $courseId = $route->getArgument('id');

        // do permission logic...
        //echo 'PermissionMiddleware';
        //return $handler->handle($request);

        // before
        /*$response = $handler->handle($request);
        $existingContent = (string) $response->getBody();

        $response = new Response();
        $response->getBody()->write('BEFORE Middleware ' . $existingContent);
        return $response;*/

        // after
        /*$response = $handler->handle($request);
        $response->getBody()->write('AFTER');
        return $response;*/



    }
}