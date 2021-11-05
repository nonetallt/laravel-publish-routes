<?php

namespace Nonetallt\Laravel\Route\Publish;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as Routes;
use Nonetallt\Laravel\Route\Publish\Configuration;
use Nonetallt\Laravel\Route\Publish\Exception\NamelessRouteException;

class RouteDataGenerator
{
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function generate() : \Generator
    {
        $routeCollection = Routes::getRoutes();

        foreach($routeCollection as $route) {

            if($this->shouldFilter($route)) {
                continue;
            }

            $name = $route->getName();
            $method = strtoupper($route->methods()[0]);
            $uri = Uri::fromUriString($route->uri());
            $extra = $this->generateExtra($route);

            if($name === null) {
                if($this->configuration->skipNameless) {
                    continue;
                }

                $msg = "Published routes must have a name! Route with a method '$method' and URI '{$route->uri()}' did not define a route name.";
                throw new NamelessRouteException($msg);
            }

            yield new RouteData($name, $method, $uri, $extra);
        }
    }

    private function shouldFilter(Route $route) : bool
    {
        return ! $this->configuration->shouldInclude($route) || $this->configuration->shouldExclude($route);
    }

    private function generateExtra(Route $route) : array
    {
        if($this->configuration->generateExtra === false) {
            return [];
        }

        return ($this->configuration->generateExtra)($route);
    }
}
