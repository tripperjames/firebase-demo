<?php

use YOOtheme\Util\Collection;

return [

    'name' => 'yootheme/styler',

    'main' => 'YOOtheme\\Theme\\Styler',

    'inject' => [

        'scripts' => 'app.scripts',
        'option' => 'app.option',

    ],

    'routes' => function ($routes) {

        $routes->get('/theme/styles', 'YOOtheme\Theme\StyleController:index');
        $routes->post('/theme/styles', 'YOOtheme\Theme\StyleController:save');

        $routes->post('/styler/library', function ($id, $style, $response) {
            $this->option->set("styler.library.{$id}", $style);
            return $response->withJson(['message' => 'success']);
        });

        $routes->delete('/styler/library', function ($id, $response) {
            $this->option->remove("styler.library.{$id}");
            return $response->withJson(['message' => 'success']);
        });

    },

    'events' => [

        'theme.admin' => [function ($theme) {

            $this->data->set('library', new Collection($this->option->get('styler.library')));

        }, -10],

        'view' => function () {
            if ($data = $this->data->all()) {
                $this->scripts->add('styler-data', sprintf('var $styler = %s;', json_encode($data)), 'customizer-styler', 'string');
            }
        },
    ],

    'config' => [

        'section' => [
            'title' => 'Style',
            'width' => 350,
            'priority' => 11,
        ],

        'fields' => [],

        'defaults' => [

            'custom_less' => '',
            'less' => [],

        ],

        'ignore_less' => [],

    ],

];
