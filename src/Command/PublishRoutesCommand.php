<?php

namespace Nonetallt\Laravel\Route\Publish\Command;

use Illuminate\Console\Command;
use Nonetallt\Laravel\Route\Publish\Configuration;
use Nonetallt\Laravel\Route\Publish\RouteDataGenerator;
use Nonetallt\Laravel\Route\Publish\ServiceProvider;

class PublishRoutesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish routes as a json file that can be utilized by route-repository javascript package.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  \App\Support\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        $outputPath = config(ServiceProvider::CONFIG_NAME . '.outputPath');
        $generateExtra = config(ServiceProvider::CONFIG_NAME . '.generateExtra') !== false;
        $flagBitmask = config(ServiceProvider::CONFIG_NAME . '.outputJsonFlags');

        $this->info("Publishing routes to '$outputPath'");

        $generator = new RouteDataGenerator(new Configuration(config('publish_routes')));
        $routes = [];

        foreach($generator->generate($outputPath) as $route) {
            $routes[] = $route->toArray($generateExtra);
            $this->line(json_encode($route->toArray($generateExtra), $flagBitmask));
        }

        file_put_contents($outputPath, json_encode($routes, $flagBitmask));

        if(count($routes) > 0) {
            $this->info('Routes generated successfully.');
        }
        else {
            $this->warn('Nothing to generate.');
        }
    }
}
