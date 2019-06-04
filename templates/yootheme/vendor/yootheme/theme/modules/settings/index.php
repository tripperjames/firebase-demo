<?php

return [

    'name' => 'yootheme/settings',

    'inject' => [

        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',

    ],

    'events' => [

        'theme.init' => function ($theme) {

            // set defaults
            $theme->merge($this->config['defaults'], true);
        },

        'theme.admin' => function ($theme) {

            // add script
            $this->scripts->add('customizer-about', "{$this->path}/app/about.min.js", 'customizer');
            $this->scripts->add('customizer-systemcheck', "{$this->path}/app/systemcheck.min.js", 'customizer');
        },

        'theme.site' => [function ($theme) {

            // set config
            $theme->merge([
                'body_class' => [$theme->get('page_class')],
                'favicon' => $this->app->url($theme->get('favicon') ?: '@assets/images/favicon.png'),
                'touchicon' => $this->app->url($theme->get('touchicon') ?: '@assets/images/apple-touch-icon.png'),
            ]);

            // combine assets
            if ($theme->get('compression') && !$this->customizer->isActive()) {
                $this->styles->combine('styles', 'theme-*', ['CssImportResolver', 'CssRewriteUrl']);
                $this->scripts->combine('scripts', '{theme-*,uikit*}');
            }

        }, 5],

    ],

    'routes' => function ($routes) {

        $routes->get('/systemcheck', 'YOOtheme\Theme\SystemCheckController:index');

        $routes->get('/cache', 'YOOtheme\Theme\CacheController:index');
        $routes->post('/cache/clear', 'YOOtheme\Theme\CacheController:clear');

    },

    'config' => [

        'section' => [
            'title' => 'Settings',
            'priority' => 60,
        ],

        'fields' => [],

        'defaults' => [],

    ],

];
