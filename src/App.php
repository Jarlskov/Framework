<?php

declare(strict_types=1);

namespace Jarlskov\Framework;

use Dotenv\Dotenv;
use Jarlskov\Framework\Providers\RouteProvider;
use Symfony\Component\HttpFoundation\Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

class App
{
    protected $routeProvider;

    /**
     * Route the current request.
     */
    public function route()
    {
        $request = Request::createFromGlobals();
        return $this->routeProvider->route($request);
    }

    /**
     * Do full bootstrap.
     */
    public function bootstrap()
    {
        $this->registerEnvironment();
        $this->registerErrorHandler();
        $this->registerRoutes();
    }

    /**
     * Load dependency injector.
     */
    public function registerDependencyInjection()
    {

    }

    /**
     * Register app's routes.
     */
    public function registerRoutes()
    {
        $this->routeProvider = new RouteProvider();
    }

    /**
     * Register env variables.
     */
    public function registerEnvironment()
    {
        $dotenv = new Dotenv(__DIR__ . '/..');
        $dotenv->load();
    }

    /**
     * Register error handler.
     */
    public function registerErrorHandler()
    {
        $environment = getenv('ENVIRONMENT');

        $whoops = new Whoops;
        if ($environment !== 'production') {
            $whoops->pushHandler(new PrettyPageHandler);
        } else {
            // @todo: This should display a pretty error page instead.
            $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
        }
        $whoops->register();
    }
}
