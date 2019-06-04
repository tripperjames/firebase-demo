<?php

use YOOtheme\Encryption;
use YOOtheme\Builder\Newsletter\NewsletterController;

return [

    'name' => 'yootheme/builder-newsletter',

    'main' => function ($app) {

        $app['encryption'] = function ($app) {
            return new Encryption($app['secret'], $app['csrf']->generate());
        };

    },

    'routes' => function ($route) {

        $controller = new NewsletterController();

        $route->post('theme/newsletter/list', [$controller, 'lists']);
        $route->post('theme/newsletter/subscribe', [$controller, 'subscribe'], ['csrf' => false, 'allowed' => true]);
    },

    'events' => [

        'builder.init' => function ($builder) {
            $builder->addTypePath("{$this->path}/elements/*/element.json");
        },

    ],

];
