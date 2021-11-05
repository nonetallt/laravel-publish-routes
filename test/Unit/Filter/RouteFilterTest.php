<?php

namespace Test\Unit\Filter;

use Illuminate\Support\Facades\Route;
use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;
use Test\Unit\TestCase;

abstract class RouteFilterTest extends TestCase
{
    protected function defineRoutes($router)
    {
        $router->get('test1')->name('test1');
        $router->get('test2')->name('test2');
        $router->get('test3')->name('test3');
    }

    protected abstract  function getFilter() : RouteFilter;

    protected abstract function shouldFilterRouteNames() : array;

    protected abstract function shouldNotFilterRouteNames() : array;

    public function testShouldFilterReturnsTrueForRoutesThatShouldBeFiltered()
    {
        $filter = $this->getFilter();

        foreach($this->getRoutesWithNames($this->shouldFilterRouteNames()) as $route) {
            $msg = "Asserted that route '{$route->getName()}' should be filtered.";
            $this->assertTrue($filter->shouldFilter($route), $msg);
        }
    }

    public function testShouldFilterReturnsFalseForRoutesThatShouldNotBeFiltered()
    {
        $filter = $this->getFilter();

        foreach($this->getRoutesWithNames($this->shouldNotFilterRouteNames()) as $route) {
            $msg = "Asserted that route '{$route->getName()}' should not be filtered.";
            $this->assertFalse($filter->shouldFilter($route, $msg));
        }
    }

    protected function getRoutesWithNames(array $names) : array
    {
        return array_filter(Route::getRoutes()->getRoutes(), function($route) use ($names) {
            return in_array($route->getName(), $names);
        });
    }
}
