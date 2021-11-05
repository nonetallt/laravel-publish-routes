<?php

namespace Test\Unit;

use Nonetallt\Laravel\Route\Publish\Configuration;
use Nonetallt\Laravel\Route\Publish\Exception\NamelessRouteException;
use Nonetallt\Laravel\Route\Publish\Filter\NameFilter;
use Nonetallt\Laravel\Route\Publish\RouteDataGenerator;
use Nonetallt\Laravel\Route\Publish\ServiceProvider;
use Test\Unit\TestCase;

class RouteDataGeneratorTest extends TestCase
{
    protected function defineRoutes($router)
    {
        $router->get('nameless');
        $router->get('test1')->name('test1');

        $router->group(['prefix' => 'foo'], function($router) {
            $router->get('test2')->name('test2');
            $router->get('test3')->name('test3');
        });
    }

    private function getDefaultConfiguration() : array
    {
        return config(ServiceProvider::CONFIG_NAME);
    }

    public function testNamelessRoutesAreSkippedWhenSkipNamelessIsTrue()
    {
        $config = $this->getDefaultConfiguration();
        $config['skipNameless'] = true;
        $generator = new RouteDataGenerator(new Configuration($config));

        $result = [];
        foreach($generator->generate() as $route) {
            $result[] = $route;
        }

        $this->assertEquals(['test1', 'foo/test2', 'foo/test3'], array_map(function($routeData) {
            return $routeData->getUri()->getComponent('path');
        }, $result));
    }

    public function testNamelessRoutesThrowNamelessRouteExceptionWhenSkipNamelessIsFalse()
    {
        $config = $this->getDefaultConfiguration();
        $config['skipNameless'] = false;
        $generator = new RouteDataGenerator(new Configuration($config));

        $this->expectException(NamelessRouteException::class);
        $result = [];
        foreach($generator->generate() as $route) {
            $result[] = $route;
        }
    }

    public function testRouteDataExtraIsEmptyWhenGenerateExtraIsFalse()
    {
        $config = $this->getDefaultConfiguration();
        $config['generateExtra'] = false;
        $generator = new RouteDataGenerator(new Configuration($config));

        $result = [];
        foreach($generator->generate() as $route) {
            $result[] = $route;
        }

        $this->assertEquals(array_fill(0, 3, []), array_map(function($routeData) {
            return $routeData->getExtra();
        }, $result));
    }

    public function testRouteDataExtraIsGeneratedWhenGenerateExtraIsCallable()
    {
        $config = $this->getDefaultConfiguration();
        $config['generateExtra'] = function($routeData) {
            return [
                'extraName' => $routeData->getName()
            ];
        };

        $generator = new RouteDataGenerator(new Configuration($config));

        $result = [];
        foreach($generator->generate() as $route) {
            $result[] = $route;
        }

        $this->assertEquals([['extraName' =>  'test1'], ['extraName' => 'test2'], ['extraName' => 'test3']], array_map(function($routeData) {
            return $routeData->getExtra();
        }, $result));
    }

    public function testNothingWillBeIncludedIfIncludeFiltersAreEmpty()
    {
        $config = $this->getDefaultConfiguration();
        $config['includeFilters'] = [];
        $generator = new RouteDataGenerator(new Configuration($config));

        $result = [];
        foreach($generator->generate() as $route) {
            $result[] = $route;
        }

        $this->assertEmpty($result);
    }

    public function testIncludeFiltersIncludeRoutes()
    {
        $config = $this->getDefaultConfiguration();
        $config['includeFilters'] = [function($route) {return true;}];
        $generator = new RouteDataGenerator(new Configuration($config));

        $result = [];
        foreach($generator->generate() as $route) {
            $result[] = $route;
        }

        $this->assertEquals(['test1', 'test2', 'test3'], array_map(function($routeData) {
            return $routeData->getName();
        }, $result));
    }

    public function testExcludeFiltersOverrideIncludeFilters()
    {
        $config = $this->getDefaultConfiguration();
        $config['includeFilters'] = [function($route) {return true;}];
        $config['excludeFilters'] = [new NameFilter('test3')];
        $generator = new RouteDataGenerator(new Configuration($config));

        $result = [];
        foreach($generator->generate() as $route) {
            $result[] = $route;
        }

        $this->assertEquals(['test1', 'test2'], array_map(function($routeData) {
            return $routeData->getName();
        }, $result));
    }
}
