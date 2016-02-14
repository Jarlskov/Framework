<?php

declare(strict_types=1);

namespace Jarlskov\Framework\Providers;

use FastRoute;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RouteProvider
{
    protected $dispatcher;

    /**
     * Initialize the route dispatcher and defined routes.
     */
    protected function initRoutes()
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
            $r->addRoute('GET', '/', 'IndexController@index');
            $r->addRoute('GET', '/{id:\d+}', 'IndexController@withId');
        });
    }

    /**
     * Handle the request routing.
     */
    public function route(Request $request)
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // @todo: Return 404 error.
                throw new \Exception('404 error');
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                // @todo Return 405 error.
                throw new \Exception('405 error');
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2]; // Matched route parameters, figure out how to handle these.

                $parts = explode('@', $handler);
                $controller = '\Jarlskov\Framework\Controllers\\' . $parts[0];
                $method = $parts[1];

                $controller = new $controller;
                return $controller->$method($vars);
                break;
        }
    }

    public function __construct()
    {
        $this->initRoutes();
    }
}
