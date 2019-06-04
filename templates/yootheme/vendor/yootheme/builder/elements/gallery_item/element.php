<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {
            if (empty($node->props['image']) && empty($node->props['hover_image'])) {
                $node->props['image'] = $params['app']->url('@assets/images/element-image-placeholder.png');
            }
        },

    ],

    'updates' => [

        '1.18.0' => function ($node, array $params) {

            if (!isset($node->props['hover_image'])) {
                $node->props['hover_image'] = @$node->props['image2'];
            }

        },

    ],

];
