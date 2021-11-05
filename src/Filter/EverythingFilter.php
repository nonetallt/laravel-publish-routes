<?php

namespace Nonetallt\Laravel\Route\Publish\Filter;

use Illuminate\Routing\Route;
use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;

class EverythingFilter implements RouteFilter
{
    public function __construct()
    {
    }

    public function shouldFilter(Route $route) : bool
    {
        return true;
    }
}
