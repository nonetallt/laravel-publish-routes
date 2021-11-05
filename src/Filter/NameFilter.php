<?php

namespace Nonetallt\Laravel\Route\Publish\Filter;

use Illuminate\Routing\Route;
use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;

class NameFilter implements RouteFilter
{
    private $names;

    public function __construct(string ...$names)
    {
        $this->names = $names;
    }

    public function shouldFilter(Route $route) : bool
    {
        return in_array($route->getName(), $this->names);
    }
}
