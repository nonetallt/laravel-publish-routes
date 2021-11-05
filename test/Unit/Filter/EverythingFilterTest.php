<?php

namespace Test\Unit\Filter;

use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;
use Nonetallt\Laravel\Route\Publish\Filter\EverythingFilter;
use Test\Unit\Filter\RouteFilterTest;

class EverythingFilterTest extends RouteFilterTest
{
    protected function defineRoutes($router)
    {
        $router->get('test1')->name('test1');
        $router->get('test2')->name('test2');
        $router->get('test3')->name('test3');
    }

    protected function getFilter() : RouteFilter
    {
        return new EverythingFilter();
    }

    protected function shouldFilterRouteNames() : array
    {
        return ['test1', 'test2', 'test3'];
    }

    protected function shouldNotFilterRouteNames() : array
    {
        return [];
    }

    /**
     * @override
     *
     */
    public function testShouldFilterReturnsFalseForRoutesThatShouldNotBeFiltered()
    {
        $this->assertTrue(true);
    }
}
