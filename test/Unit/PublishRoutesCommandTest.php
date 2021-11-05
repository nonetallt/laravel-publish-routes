<?php

namespace Test\Unit;

use Nonetallt\Laravel\Route\Publish\ServiceProvider;
use Test\Unit\TestCase;

class PublishRoutesCommandTest extends TestCase
{
    protected function defineRoutes($router)
    {
        $router->get('test1')->name('test1');

        $router->group(['prefix' => 'foo', 'middleware' => ['api']], function($router) {
            $router->get('test2')->name('test2');
            $router->get('test3')->name('test3');
        });
    }

    private function getOutputPath() : string
    {
        return config(ServiceProvider::CONFIG_NAME . '.outputPath');
    }

    protected function setUp() : void
    {
        parent::setUp();

        $outputPath = dirname(__DIR__) . '/output/routes.json';
        config([ServiceProvider::CONFIG_NAME . '.outputPath' => $outputPath]);

        if(file_exists($this->getOutputPath())) {
            unlink($this->getOutputPath());
        }
    }

    protected function tearDown() : void
    {
        parent::setUp();

        if(file_exists($this->getOutputPath())) {
            unlink($this->getOutputPath());
        }
    }

    public function testOutputDoesNotExistBeforeRunningTests()
    {
        $this->assertFalse(file_exists($this->getOutputPath()));
    }

    public function testCommandGeneratesOutputFile()
    {
        $this->artisan('route:publish');
        $this->assertTrue(file_exists($this->getOutputPath()));
    }

    public function testCommandOutputMatchesExpectedOutput()
    {
        $this->artisan('route:publish');
        // Trim because of unix editor line endings.
        $expected = trim(file_get_contents(dirname(__DIR__) . '/input/routes_with_uri_components.json'));
        $result = file_get_contents($this->getOutputPath());
        $this->assertEquals($expected, $result);
    }

    public function testCommandOutputHasExtraContentWhenGenerateExtraIsSet()
    {
        config([ServiceProvider::CONFIG_NAME . '.generateExtra' => function($route) {
            return [
                'middlewares' => $route->gatherMiddleware()
            ];
        }]);

        $this->artisan('route:publish');

        // Trim because of unix editor line endings.
        $expected = trim(file_get_contents(dirname(__DIR__) . '/input/routes_with_middleware_extra.json'));
        $result = file_get_contents($this->getOutputPath());
        $this->assertEquals($expected, $result);
    }
}
