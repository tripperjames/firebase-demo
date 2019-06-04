<?php

return [

    'name' => 'yootheme/theme',

    'main' => function ($app) {

        $app['locator']
            ->addPath("{$this->path}/builder", 'builder')
            ->addPath("{$this->path}/assets", 'assets')
            ->addPath("{$this->path}/platforms", 'assets/platforms');

        $app->extend('image', function ($image) use ($app) {

            $convert = $this->theme->get('webp') && is_callable('imagewebp') && strpos($app['request']->getHeaderLine('Accept'), 'image/webp') !== false
                ? ['png' => 'webp,100', 'jpeg' => 'webp,85']
                : false;

            $image->addLoader(function ($image) use ($convert) {

                $params = $image->getAttribute('params', []);

                // convert image type?
                if ($convert && !isset($params['type'])) {

                    $type = $image->getType();

                    if (isset($convert[$type])) {
                        $params['type'] = $convert[$type];
                    }

                }

                // image covers
                if (isset($params['covers']) && $params['covers'] && !isset($params['sizes'])) {
                    $img = $image->apply($params);
                    $ratio = round($img->width / $img->height * 100);
                    $params['sizes'] = "(max-aspect-ratio: {$img->width}/{$img->height}) {$ratio}vh";
                }

                // set default srcset
                if (isset($params['srcset']) && $params['srcset'] === '1') {
                    $params['srcset'] = '768,1024,1366,1600,1920,200%';
                }

                $image->setAttribute('params', $params);

            });

        });

        $app->extend('routes', function ($routes) use ($app) {
            $routes->getRoute('@image')->setAttribute('save', !$this->customizer->isActive());
        });

    },

    'require' => 'yootheme/framework',

    'include' => [

        'modules/layout/index.php',
        'modules/settings/index.php',
        '../builder*/index.php',
        '../styler/index.php',
        '../theme-*/index.php',
        'platforms/*/index.php',

    ],

    'inject' => [

        'view' => 'app.view',
        'image' => 'app.image',
        'assets' => 'app.assets',
        'builder' => 'app.builder',
        'modules' => 'app.modules',
        'scripts' => 'app.scripts',
        'translator' => 'app.translator',
        'customizer' => 'theme.customizer',

    ],

    'events' => [

        'theme.init' => function ($theme) {

            $this->assets->setVersion($theme->options['version']);
            $this->scripts->register('runtime', "{$this->path}/app/runtime.min.js", 'config');
            $this->scripts->register('commons', "{$this->path}/app/commons.min.js", 'runtime');
            $this->scripts->register('app-config', "{$this->path}/app/config.min.js", 'runtime');
            $this->scripts->register('uikit', 'vendor/assets/uikit/dist/js/uikit' . (!$theme->debug ? '.min' : '') . '.js');
            $this->scripts->register('uikit-icons', 'vendor/assets/uikit/dist/js/uikit-icons.min.js', '~uikit');

        },

        'theme.site' => function () {
            if ($this->customizer->isActive()) {
                $this->scripts->add('customizer-site', "{$this->path}/assets/js/customizer.min.js", 'uikit');
            }
        },

        'theme.admin' => [function ($theme) {

            // Load Footer builder
            $footer = $theme->get('footer.content');
            $theme->set('footer.content', $this->builder->load(
                $footer ? json_encode($footer->all()) : '{}'
            ));

            $this->customizer->mergeData([
                'name' => $theme->name,
                'base' => $this->app->url($theme->path),
                'api' => 'https://yootheme.com/api',
                'help' => 'https://yootheme.com',
                'version' => $theme->options['version'],
            ]);

            foreach ($this->modules->all() as $module) {

                if ($section = $module->config->get('section')) {

                    if ($fields = $module->config->get('fields')) {
                        $section['fields'] = $fields;
                    }

                    $this->customizer->addSection(basename($module->name), $section);
                }

                if ($panels = $module->config->get('panels')) {
                    $this->customizer->addData('panels', $panels);
                }
            }

            $this->translator->addResource("{$this->path}/languages/{locale}.json");

        }, -10],

    ],

];
