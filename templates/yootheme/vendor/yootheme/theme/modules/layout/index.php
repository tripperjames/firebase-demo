<?php

return [

    'name' => 'yootheme/layout',

    'events' => [

        'theme.init' => function ($theme) {

            // set defaults
            $theme->merge($this->config['defaults'], true);
        },

    ],

    'config' => [

        'section' => [
            'title' => 'Layout',
            'priority' => 10,
        ],

        'fields' => [],

        'defaults' => [

            'menu' => [

                'items' => [],
                'positions' => [],

            ],

        ],

    ],

];
