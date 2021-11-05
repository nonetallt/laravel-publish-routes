<?php

namespace Nonetallt\Laravel\Route\Publish\Contract;

use Illuminate\Routing\Route;

interface RouteFilter
{
    public function shouldFilter(Route $route) : bool;
}
