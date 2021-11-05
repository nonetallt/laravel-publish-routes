<?php

namespace Nonetallt\Laravel\Route\Publish;

class RouteDataGeneratorConfiguration
{
    private $outputPath;
    private $outputJsonFlags;
    private $skipNameless;
    private $generateExtra;
    private $include;
    private $exclude;

    public function __construct()
    {

    }

    public static function fromArray(array $config) : self
    {

    }
}
