<?php

namespace Nonetallt\Laravel\Route\Publish\Filter;

use Illuminate\Routing\Route;
use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;

class CallableFilter implements RouteFilter
{
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function shouldFilter(Route $route) : bool
    {
        $returnValue = ($this->callable)($route);

        if(! is_bool($returnValue)) {
            $type = gettype($returnValue) === 'object' ? get_class($returnValue) : gettype($returnValue);
            $msg = "Unexpected return value from filter: boolean expected, got $type";
            throw new \Exception($msg);
        }

        return $returnValue;
    }
}
