<?php

use YOOtheme\Builder;
use YOOtheme\Builder\ConfigManager;
use YOOtheme\Builder\DefaultTransform;
use YOOtheme\Builder\ElementTransform;
use YOOtheme\Builder\NormalizeTransform;
use YOOtheme\Builder\PlaceholderTransform;
use YOOtheme\Builder\UpdateTransform;
use YOOtheme\Util\File;

return [

    'name' => 'yootheme/builder',

    'main' => function ($app) {

        $this['builder'] = function () use ($app) {

            $config = new ConfigManager($app['path.cache'], compact('app'));
            $config->add('builder', "{$this->path}/params.json");
            $config->add('url', function ($value, $file) use ($app) {

                if ($value[0] !== '@') {
                    $value = File::resolvePath(dirname($file), $value);
                }

                return $app->url($value);
            });

            $builder = new Builder([$config, 'load'], [$app['view'], 'render'], [
                'app' => $app,
                'view' => $app['view'],
                'theme' => $app->theme,
            ]);

            $app->trigger('builder.init', [$builder]);

            return $builder;
        };

        $app['builder'] = function () {
            return $this['builder'];
        };

    },

    'inject' => [
        'option' => 'app.option',
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',
    ],

    'routes' => function ($route) {

        $route->post('/builder/encode', function ($layout, $response) {
            return $response->withJson($this->builder->load(json_encode($layout)));
        });

        $route->post('/builder/library', function ($id, $element, $response) {

            $this->option->set("library.{$id}", $element);

            return $response->withJson(['message' => 'success']);
        });

        $route->delete('/builder/library', function ($id, $response) {

            $this->option->remove("library.{$id}");

            return $response->withJson(['message' => 'success']);
        });

    },

    'events' => [

        'builder.init' => function ($builder) {
            $builder->addTypePath("{$this->path}/elements/*/element.json");
            $builder->addTransform('preload', new UpdateTransform($this->theme->options['version']));
            $builder->addTransform('preload', new DefaultTransform());
            $builder->addTransform('precontent', new NormalizeTransform());
        },

        'theme.site' => function ($theme) {

            $this->builder->addTransform('preload', function ($node, $params) {

                static $id;

                /**
                 * @var $type
                 * @var $prefix
                 */
                extract($params);

                if ($node->type == 'layout') {
                    $id = 0;
                }

                if (($type->element || $type->container) && isset($prefix)) {

                    $node->id = "{$prefix}#".$id++;

                    if ($this->customizer->isActive() && $type->element) {
                        $node->attrs['data-id'] = $node->id;
                    }
                }

            });

            if (!$this->customizer->isActive()) {
                $this->builder->addTransform('prerender', function ($node, $params) {
                    if (isset($node->props['status']) && $node->props['status'] === 'disabled') {
                        return false;
                    }
                });
            }

            $this->builder->addTransform('prerender', new NormalizeTransform());
            $this->builder->addTransform('prerender', new PlaceholderTransform());
            $this->builder->addTransform('render', new ElementTransform());
        },

        'theme.admin' => [function () {

            $library = array_map('json_encode', $this->option->get('library', []));

            $data = json_encode([
                'elements' => $this->builder->types,
                'library' => array_map([$this->builder, 'load'], $library),
            ]);

            $this->scripts->add('builder', "{$this->path}/app/builder.min.js", 'customizer');
            $this->scripts->add('builder-data', "var \$builder = {$data};", 'builder', 'string');

        }, -10],

    ],

    'config' => [

        'section' => [
            'title' => 'Builder',
            'heading' => false,
            'width' => 500,
            'priority' => 20,
        ],

        'panels' => [

            'builder-parallax' => [

                'title' => 'Parallax',
                'width' => 500,
                'fields' => [

                    'parallax_x' => [
                        'type' => 'grid',
                        'width' => '1-2',
                        'fields' => [

                            'parallax_x_start' => [
                                'label' => 'Horizontal Start',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => -600,
                                    'max' => 600,
                                    'step' => 10,
                                ],
                            ],

                            'parallax_x_end' => [
                                'label' => 'Horizontal End',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => -600,
                                    'max' => 600,
                                    'step' => 10,
                                ],
                            ],

                        ],
                    ],

                    'parallax_y' => [
                        'type' => 'grid',
                        'width' => '1-2',
                        'fields' => [

                            'parallax_y_start' => [
                                'label' => 'Vertical Start',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => -600,
                                    'max' => 600,
                                    'step' => 10,
                                ],
                            ],

                            'parallax_y_end' => [
                                'label' => 'Vertical End',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => -600,
                                    'max' => 600,
                                    'step' => 10,
                                ],
                            ],

                        ],
                    ],

                    'parallax_scale' => [
                        'type' => 'grid',
                        'width' => '1-2',
                        'fields' => [

                            'parallax_scale_start' => [
                                'label' => 'Scale Start',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => 0.5,
                                    'max' => 2,
                                    'step' => 0.1,
                                ],
                            ],

                            'parallax_scale_end' => [
                                'label' => 'Scale End',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => 0.5,
                                    'max' => 2,
                                    'step' => 0.1,
                                ],
                            ],

                        ],
                    ],

                    'parallax_rotate' => [
                        'type' => 'grid',
                        'width' => '1-2',
                        'fields' => [

                            'parallax_rotate_start' => [
                                'label' => 'Rotate Start',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => 0,
                                    'max' => 360,
                                    'step' => 10,
                                ],
                            ],

                            'parallax_rotate_end' => [
                                'label' => 'Rotate End',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => 0,
                                    'max' => 360,
                                    'step' => 10,
                                ],
                            ],

                        ],
                    ],

                    'parallax_opacity' => [
                        'type' => 'grid',
                        'width' => '1-2',
                        'fields' => [

                            'parallax_opacity_start' => [
                                'label' => 'Opacity Start',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => 0,
                                    'max' => 1,
                                    'step' => 0.1,
                                ],
                            ],

                            'parallax_opacity_end' => [
                                'label' => 'Opacity End',
                                'type' => 'range',
                                'attrs' => [
                                    'min' => 0,
                                    'max' => 1,
                                    'step' => 0.1,
                                ],
                            ],

                        ],
                    ],

                    'parallax_easing' => [
                        'label' => 'Easing',
                        'description' => 'Set the animation easing. A value below 1 is faster in the beginning and slower towards the end while a value above 1 behaves inversely.',
                        'type' => 'range',
                        'attrs' => [
                            'min' => 0.1,
                            'max' => 2,
                            'step' => 0.1,
                        ],
                    ],

                    'parallax_viewport' => [
                        'label' => 'Viewport',
                        'description' => 'Set the animation end point relative to viewport height, e.g. <code>0.5</code> for 50% of the viewport',
                        'type' => 'range',
                        'attrs' => [
                            'min' => 0.1,
                            'max' => 1,
                            'step' => 0.1,
                        ],
                    ],

                    'parallax_target' => [
                        'label' => 'Target',
                        'type' => 'checkbox',
                        'text' => 'Animate the element as long as the section is visible',
                    ],

                    'parallax_zindex' => [
                        'label' => 'Z Index',
                        'type' => 'checkbox',
                        'text' => 'Set a higher stacking order.',
                    ],

                    'parallax_breakpoint' => [
                        'label' => 'Breakpoint',
                        'description' => 'Display the parallax effect only on this device width and larger.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Always' => '',
                            'Small (Phone Landscape)' => 's',
                            'Medium (Tablet Landscape)' => 'm',
                            'Large (Desktop)' => 'l',
                            'X-Large (Large Screens)' => 'xl',
                        ],
                    ],

                ],

            ],

        ],

    ],

];
