<?php

namespace Test\Unit\Filter;

use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;
use Nonetallt\Laravel\Route\Publish\Filter\NameFilter;
use Test\Unit\Filter\RouteFilterTest;

class NameFilterTest extends RouteFilterTest
{
    protected function defineRoutes($router)
    {
        $router->get('test1')->name('test1');
        $router->get('test2')->name('test2');
        $router->get('test3')->name('test3');
    }

    protected function getFilter() : RouteFilter
    {
        return new NameFilter('test2', 'test3');
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
