# nonetallt/laravel-publish-routes

Server-side integration for the [route-repository](https://github.com/nonetallt/route-repository) javascript package.

This package allows you to generate a json file containing your Laravel application routes.

## Installation

Use composer:

```
composer install nonetallt/laravel-publish-routes --dev
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

Note that parsing can get funky for complex regex matching URI patterns - such routes should not be included.

## Configuration

### Filtering routes

[
            /* 'prefix' => $route->getPrefix(), */
            /* 'controller' => $route->getController(), */
            /* 'controllerMethod' => $route->getActionName(), */
            /* 'middleware' => $route->middleware(), */
            /* 'domain' => $route->getDomain() */
        ]

Available filters provided by this package:

- Nonetallt\Laravel\Route\Publish\Filter\CallableFilter
- Nonetallt\Laravel\Route\Publish\Filter\EverythingFilter
- Nonetallt\Laravel\Route\Publish\Filter\MiddlewareFilter
- Nonetallt\Laravel\Route\Publish\Filter\NameFilter
- Nonetallt\Laravel\Route\Publish\Filter\NothingFilter
- Nonetallt\Laravel\Route\Publish\Filter\PrefixFilter
