<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) use ($config) {

            /**
             * @var $app
             */
            extract($params);

            $node->form = [
                'action' => $app->route('theme/newsletter/subscribe'),
            ];

            $provider = (array) $node->props['provider'];
            $node->settings = $app['encryption']->encrypt(array_merge(
                $provider, (array) $node->props[$provider['name']]
            ));

            $app['scripts']->add('newsletter', $config->get('url:../../app/newsletter.min.js'), [], ['defer' => true]);
        },

    ],

];
