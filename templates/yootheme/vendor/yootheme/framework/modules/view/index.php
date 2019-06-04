<?php

use YOOtheme\Util\File;
use YOOtheme\Util\Str;
use YOOtheme\View;
use YOOtheme\View\MetadataHelper;

return [

    'name' => 'yootheme/view',

    'main' => function ($app) {

        $app['view'] = function ($app) {

            $view = new View($app['view.loader']);
            $view->addGlobal('app', $app);
            $view->addGlobal('view', $view);
            $view->addHelper('YOOtheme\View\StrHelper');
            $view->addHelper('YOOtheme\View\HtmlHelper');
            $view->addHelper('YOOtheme\View\SectionHelper');
            $view->addHelper([$app['metadata'], 'register']);

            return $view;
        };

        $app['view.loader'] = $app->protect(function ($name, $parameters, $next) {

            $find = function ($name) {

                if (!Str::endsWith($name, '.php')) {
                    $name .= '.php';
                }

                if (File::isAbsolute($name)) {
                    return is_file($name) ? $name : false;
                }

                if ($name[0] != '@') {
                    $name = "@views/{$name}";
                }

                return File::find($name);
            };

            if (strpos($name, ':') && preg_match('/(.+):([\w-]+)$/', $name, $matches)) {
                $name = $find("{$matches[1]}-{$matches[2]}") ?: $matches[1];
            }

            return $next($find($name) ?: $name, $parameters);
        });

        $app['metadata'] = function () {
            return new MetadataHelper();
        };

    }

];
