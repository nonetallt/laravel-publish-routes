<?php

namespace Nonetallt\Laravel\Route\Publish;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Nonetallt\Laravel\Route\Publish\Command\PublishRoutesCommand;

class ServiceProvider extends BaseServiceProvider
{
    const CONFIG_NAME = 'publish_routes';

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', self::CONFIG_NAME);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path(self::CONFIG_NAME . '.php')
        ]);

        if($this->app->runningInConsole()) {
            $this->commands([
                PublishRoutesCommand::class
            ]);
        }
    }
}
