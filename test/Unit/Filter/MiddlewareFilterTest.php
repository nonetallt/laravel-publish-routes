<?php

namespace Test\Unit\Filter;

use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;
use Nonetallt\Laravel\Route\Publish\Filter\MiddlewareFilter;
use Test\Unit\Filter\RouteFilterTest;

class MiddlewareFilterTest extends RouteFilterTest
{
    protected function defineRoutes($router)
    {
        $router->get('test1')->name('test1');
        $router->get('test2')->name('test2')->middleware('foo');
        $router->get('test3')->name('test3')->middleware('bar');
    }

    protected function getFilter() : RouteFilter
    {
        return new MiddlewareFilter('foo', 'bar');
    }

    protected function shouldFilterRouteNames() : array
    {
        return ['test2', 'test3'];
    }

    protected function shouldNotFilterRouteNames() : array
    {
        return ['test1'];
    }
}
