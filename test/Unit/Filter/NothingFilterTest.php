<?php

namespace Test\Unit\Filter;

use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;
use Nonetallt\Laravel\Route\Publish\Filter\NothingFilter;
use Test\Unit\Filter\RouteFilterTest;

class NothingFilterTest extends RouteFilterTest
{
    protected function defineRoutes($router)
    {
        $router->get('test1')->name('test1');
        $router->get('test2')->name('test2');
        $router->get('test3')->name('test3');
    }

    protected function getFilter() : RouteFilter
    {
        return new NothingFilter();
    }

    protected function shouldFilterRouteNames() : array
    {
        return [];
    }

    protected function shouldNotFilterRouteNames() : array
    {
        return ['test1', 'test2', 'test3'];
    }

    /**
     * @override
     *
     */
    public function testShouldFilterReturnsTrueForRoutesThatShouldBeFiltered()
    {
        $this->assertTrue(true);
    }
}
