<?php

$config = [

    'name' => 'yootheme/joomla-modules',

    'main' => 'YOOtheme\\Theme\\Modules',

    'inject' => [
        'db' => 'app.db',
        'view' => 'app.view',
        'styles' => 'app.styles',
        'builder' => 'app.builder',
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',
    ],

    'routes' => function ($routes) {

        $routes->get('/modules', function ($response) {
            return $response->withJson($this->modules);
        });

        $routes->get('/module', function ($id, $response) {

            $query = 'SELECT id, content FROM @modules WHERE id = :id';
            $module = $this->db->fetchObject($query, ['id' => $id]);
            $module->content = $this->builder->load($module->content);

            return $response->withJson($module);
        });

        $routes->post('/module', function ($id, $content, $response) {

            $this->db->update('@modules', [
                'content' => json_encode($content),
            ], ['id' => $id]);

            return $response->withJson(['message' => 'success']);
        });

        $routes->get('/positions', function ($response) {
            return $response->withJson($this->positions);
        });

    },

    'config' => [

        'section' => [
            'title' => 'Modules',
            'priority' => 40,

            'help' => [
                [
                    'title' => 'Modules',
                    'src' => 'site/docs/yootheme-pro/widgets-and-modules/joomla/videos/modules.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widgets-and-modules',
                    'support' => 'support/search?tags=125&q=modules'
                ],
                [
                    'title' => 'Creating a Module',
                    'src' => 'site/docs/yootheme-pro/widgets-and-modules/joomla/videos/create-module.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widgets-and-modules#module-customizer',
                    'support' => 'support/search?tags=125&q=modules'
                ],
                [
                    'title' => 'Positions',
                    'src' => 'site/docs/yootheme-pro/widgets-and-modules/joomla/videos/positions.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widgets-and-modules#module-positions',
                    'support' => 'support/search?tags=125&q=module%20positions'
                ],
                [
                    'title' => 'Module Options - Default',
                    'src' => 'site/docs/yootheme-pro/widget-and-module-settings/joomla/videos/module-theme-options-default.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widget-and-module-settings#default-options',
                    'support' => 'support/search?tags=125&q=module%20theme%20settings'
                ],
                [
                    'title' => 'Module Options - Appearance',
                    'src' => 'site/docs/yootheme-pro/widget-and-module-settings/joomla/videos/module-theme-options-appearance.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widget-and-module-settings#appearance-options',
                    'support' => 'support/search?tags=125&q=module%20theme%20settings'
                ],
                [
                    'title' => 'Module Options - Grid',
                    'src' => 'site/docs/yootheme-pro/widget-and-module-settings/joomla/videos/module-theme-options-grid.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widget-and-module-settings#grid-options',
                    'support' => 'support/search?tags=125&q=module%20theme%20settings%20grid'
                ],
                [
                    'title' => 'Module Options - List',
                    'src' => 'site/docs/yootheme-pro/widget-and-module-settings/joomla/videos/module-theme-options-list.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widget-and-module-settings#list-options',
                    'support' => 'support/search?tags=125&q=module%20theme%20settings%20list'
                ],
                [
                    'title' => 'Module Options - Menu',
                    'src' => 'site/docs/yootheme-pro/widget-and-module-settings/joomla/videos/module-theme-options-menu.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widget-and-module-settings#menu-options',
                    'support' => 'support/search?tags=125&q=module%20theme%20settings%20menu'
                ],
                [
                    'title' => 'Module Visibility',
                    'src' => 'site/docs/yootheme-pro/widget-and-module-settings/joomla/videos/module-visibility.mp4',
                    'documentation' => 'support/yootheme-pro/joomla/widget-and-module-settings#module-visibility',
                    'support' => 'support/search?tags=125&q=module%20visibility'
                ],
            ],

        ],

        'fields' => [],

        'defaults' => [],

    ],

];

return defined('_JEXEC') ? $config : false;
