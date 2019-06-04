<?php

namespace YOOtheme;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Http\Uri;
use YOOtheme\Util\File;
use YOOtheme\Util\Filter;

return [

    'name' => 'yootheme/app',

    'main' => function ($app) {

        $app['uri'] = function () {
            return Uri::fromGlobals();
        };

        $app['request'] = function ($app) {
            return Request::fromGlobals($app['uri']);
        };

        $app['response'] = function () {
            return new Response();
        };

        $app['kernel'] = function () {
            return new Kernel();
        };

        $app['router'] = function ($app) {
            return new Router($app['routes']);
        };

        $app['routes'] = function () {
            return new RouteCollection();
        };

        $app['locator'] = function () {
            return File::getLocator();
        };

        $app['filters'] = function () {
            return Filter::getManager();
        };

        $app['modules']->addLoader(function ($options, $next) use ($app) {

            $module = $next($options);
            $routes = isset($module->options['routes']) ? $module->options['routes'] : null;

            if ($routes instanceof \Closure) {
                $app->extend('routes', $routes->bindTo($module, $module));
            }

            return $module;
        });

    },

    'require' => 'yootheme/event',

    'events' => [

        'init' => [function ($app) {

            $app['url']->addResolver(function ($path, $parameters, $secure, $next) use ($app) {

                $file = File::isRelative($path) ? File::find($path) : $path;

                if ($file && @stripos($file, $app->path) === 0) {
                    $path = ltrim(substr($file, strlen($app->path)), '/');
                }

                return $next($path, $parameters, $secure);
            });

            if ($app->path) {
                $app['locator']->addPath($app->path);
            }

        }, 10],

        'error' => function ($request, $response, $e) {

            if (strpos($request->getContentType(), 'application/json') === 0) {
                return $response->withStatus($e->getCode() ?: 500, $e->getMessage())->withJson($e->getMessage());
            }
        }

    ]

];
