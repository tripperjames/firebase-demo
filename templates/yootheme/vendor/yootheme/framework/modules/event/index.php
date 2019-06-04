<?php

use YOOtheme\EventManager;
use YOOtheme\Module\EventLoader;

return [

    'name' => 'yootheme/event',

    'main' => function ($app) {

        $app['events'] = function () {
            return new EventManager();
        };

        $app['modules']->addLoader(new EventLoader($app['events']));
    }

];
