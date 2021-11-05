<?php

namespace Nonetallt\Laravel\Route\Publish\Filter;

use Illuminate\Routing\Route;
use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;

class PrefixFilter implements RouteFilter
{
    private $prefixes;

    public function __construct(string ...$prefixes)
    {
        $this->prefixes = $prefixes;
    }

    public function shouldFilter(Route $route) : bool
    {
        return in_array($route->getPrefix(), $this->prefixes);
    }
}
