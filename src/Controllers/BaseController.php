<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Views\Twig;
use App\Controllers\Interfaces\InterfaceController;

abstract class BaseController implements InterfaceController
{
    protected $app;
    protected $response;
    protected $config;
    public function __construct(App $app, ResponseInterface $response, array $config = [])
    {
        $this->app = $app;
        $this->response = $response;
        $this->config = $config;
    }

    abstract public function display(): ResponseInterface;
}