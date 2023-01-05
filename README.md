# nonetallt/laravel-publish-routes

Server-side integration for the [route-repository](https://github.com/nonetallt/route-repository) javascript package.

This package allows you to generate a json file containing your Laravel application routes.

## Installation

Use composer:

```
composer require nonetallt/laravel-publish-routes --dev
```

## Basic usage

Publish the configuration:

```
php artisan vendor:publish --provider="Nonetallt\Laravel\Route\Publish\ServiceProvider"
```

Run the command whenever your routes update:

```
php artisan route:publish
```

For the optimal workflow experience, it is recommended to use a task runner with a file watcher to automatically run the command whenever your routes update.


## Caveats

Note that parsing can get funky for regex matching URI patterns - such routes should not be published.

## Configuration

#### outputPath

- Type: string

The filepath for the generated route file. Should have the json extension.

#### outputJsonFlags

- Type: int bitmask

The 2nd argument of [json_encode](https://www.php.net/manual/en/function.json-encode.php). Used for encoding the resulting output.

#### skipNameless

- Type: bool

Whether routes that do not define a name should be ignored. The route-repository package relies on route names, so all this option does is throw `Nonetallt\Laravel\Route\Publish\Exception\NamelessRouteException` whenever you attempt to publish a route with no name. This is provided for convenience's sake: you can set the option to true so you don't forget to name a route and then have it silently ignored by the publish command.

#### generateExtra

- Type: callable(`Illuminate\Routing\Route`) : array | `false`

If set to `false`, no extra field is generated for published routes. If set to a callback, the function takes `Illuminate\Routing\Route` as the first argument and should return an array with string keys. This allows you to use the route data to publish whatever extra information you deem important.


#### includeFilters

- Type: array[callable(`Illuminate\Routing\Route`) : bool | `Nonetallt\Laravel\Route\Publish\Contract\RouteFilter`]

An array that can contain:

- Callables that take `Illuminate\Routing\Route` as first argument and return bool.
- Instances implementing the `Nonetallt\Laravel\Route\Publish\Contract\RouteFilter` interface.

These filters define which routes should be included when publishing routes. Routes not matched by these filters will not be published.

#### excludeFilters

- Type: array[callable(`Illuminate\Routing\Route`) : bool | `Nonetallt\Laravel\Route\Publish\Contract\RouteFilter`]

An array that can contain:

- Callables that take `Illuminate\Routing\Route` as first argument and return bool.
- Instances implementing the `Nonetallt\Laravel\Route\Publish\Contract\RouteFilter` interface.

These filters defined which routes should be exlcuded when publishing routes. Routes matched by these filters will not be published. Supersedes [includeFilters](#includeFilters). If you wish to only define which routes to exclude, you should use the `Nonetallt\Laravel\Route\Publish\Filter\EverythingFilter` in includeFilters and then exclude the desired routes.

## Available Filters

The following filters are provided by the package out of the box:

- Nonetallt\Laravel\Route\Publish\Filter\CallableFilter
- Nonetallt\Laravel\Route\Publish\Filter\EverythingFilter
- Nonetallt\Laravel\Route\Publish\Filter\MiddlewareFilter
- Nonetallt\Laravel\Route\Publish\Filter\NameFilter
- Nonetallt\Laravel\Route\Publish\Filter\NothingFilter
- Nonetallt\Laravel\Route\Publish\Filter\PrefixFilter

You can simply use callbacks to quickly define your own custom filters, as callbacks are converted into instances of `Nonetallt\Laravel\Route\Publish\Filter\CallableFilter`. Refer to the [Route API](https://laravel.com/api/8.x/Illuminate/Routing/Route.html) to see available methods you might use for this purpose.

If you wish to create more complex filters that you might for example, share across multiple projects, you should implement the `Nonetallt\Laravel\Route\Publish\Contract\RouteFilter` interface. The interface has only one method you must implement:

```php
public function shouldFilter(Illuminate\Routing\Route $route) : bool;
```
