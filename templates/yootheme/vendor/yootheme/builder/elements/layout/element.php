<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            /**
             * @var $app
             * @var $prefix
             */
            extract($params);

            // Add elements inline css
            if (!empty($node->props['css'])) {
                $css = preg_replace('/[\r\n\t]+/', ' ', $node->props['css']);
                $app['styles']->add("builder-{$prefix}", $css, [], ['type' => 'string', 'defer' => true]);
            }

        },

    ],

];
