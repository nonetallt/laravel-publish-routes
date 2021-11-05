<?php

namespace Nonetallt\Laravel\Route\Publish;

use Illuminate\Routing\Route;
use Nonetallt\Laravel\Route\Publish\Contract\RouteFilter;
use Nonetallt\Laravel\Route\Publish\Filter\CallableFilter;
use Nonetallt\Laravel\Route\Publish\ServiceProvider;

class Configuration
{
    const KEYS = [
        'skipNameless',
        'generateExtra',
        'includeFilters',
        'excludeFilters'
    ];

    private $values;

    public function __construct(array $values)
    {
        $this->values = [];

        foreach(self::KEYS as $key) {

            if(! array_key_exists($key, $values)) {
                $msg = "Missing required configuration value '$key'.";
                throw new \InvalidArgumentException($msg);
            }

            $setterSignature = 'set' . ucfirst($key);

            if(method_exists($this, $setterSignature)) {
                $this->values[$key] = $this->$setterSignature($values[$key]);
            }
        }
    }

    private function setSkipNameless(bool $skipNameless)
    {
        return $skipNameless;
    }

    private function setGenerateExtra($generateExtra)
    {
        if(is_callable($generateExtra) || is_bool($generateExtra)) {
            return $generateExtra;
        }

        $msg = 'Configuration option generate extra should be either a callable or bool.';
        throw new \InvalidArgumentException($msg);
    }

    private function setIncludeFilters(array $array)
    {
        return $this->setFilter('Include', $array);
    }

    public function shouldInclude(Route $route) : bool
    {
        return $this->shouldFilter($route, ...$this->values['includeFilters']);
    }

    private function setExcludeFilters($array)
    {
        return $this->setFilter('Exclude', $array);
    }

    public function shouldExclude(Route $route) : bool
    {
        return $this->shouldFilter($route, ...$this->values['excludeFilters']);
    }

    public function __get(string $key)
    {
        if(array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        $msg = "Configuration value '$key' does not exist.";
        throw new \Exception($msg);
    }

    private function setFilter(string $name, array $array) : array
    {
        $filters = [];

        foreach($array as $index => $exclude) {
            if($exclude instanceof RouteFilter) {
                $filters[] = $exclude;
                continue;
            }

            if(is_callable($exclude)) {
                $filters[] = new CallableFilter($exclude);
                continue;
            }

            $msg = "$name filter at index '$index' is not a valid filter.";
            throw new \InvalidArgumentException($msg);
        }

        return $filters;
    }

    private function shouldFilter(Route $route, RouteFilter ...$filters) : bool
    {
        foreach($filters as $filter) {
            if($filter->shouldFilter($route)) {
                return true;
            }
        }

        return false;
    }
}
