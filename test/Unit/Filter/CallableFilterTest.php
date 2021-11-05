<?php

namespace Test\Unit\Filter;

use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;
use Nonetallt\Laravel\Route\Publish\Filter\CallableFilter;
use Test\Unit\Filter\RouteFilterTest;

class CallableFilterTest extends RouteFilterTest
{
    protected function defineRoutes($router)
    {
        $router->get('test1')->name('test1');
        $router->get('test2')->name('test2');
        $router->get('test3')->name('test3');
    }

    protected function getFilter() : RouteFilter
    {
        return new CallableFilter(function($route) {
            return $route->getName() === 'test1';
        });
    }

    protected function shouldFilterRouteNames() : array
    {
        return ['test1'];
    }

    protected function shouldNotFilterRouteNames() : array
    {
        return ['test2', 'test3'];
    }
}
