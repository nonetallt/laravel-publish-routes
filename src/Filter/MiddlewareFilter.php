<?php

namespace Nonetallt\Laravel\Route\Publish\Filter;

use Illuminate\Routing\Route;
use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;

class MiddlewareFilter implements RouteFilter
{
    private $middlewares;

    public function __construct(string ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function shouldFilter(Route $route) : bool
    {
        return count(array_intersect($route->gatherMiddleware(), $this->middlewares)) > 0;
    }
}
