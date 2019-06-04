<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            if (empty($node->props['image']) && empty($node->props['video'])) {
                $node->props['image'] = $params['app']->url('@assets/images/element-image-placeholder.png');
            }

        },

    ],

];
