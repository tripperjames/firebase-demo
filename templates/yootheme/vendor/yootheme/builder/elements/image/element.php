<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            if (empty($node->props['image'])) {
                $node->props['image'] = $params['app']->url('@assets/images/element-image-placeholder.png');
            }

        },

    ],

    'updates' => [

        '1.18.10.1' => function ($node, array $params) {

            if (isset($node->props['image_inline_svg'])) {
                $node->props['image_svg_inline'] = $node->props['image_inline_svg'];
                unset($node->props['image_inline_svg']);
            }

            if (isset($node->props['image_animate_svg'])) {
                $node->props['image_svg_animate'] = $node->props['image_animate_svg'];
                unset($node->props['image_animate_svg']);
            }

        },

        '1.18.0' => function ($node, array $params) {

            if (@$node->props['link_target'] === true) {
                $node->props['link_target'] = 'blank';
            }

            if (!isset($node->props['image_box_decoration']) && @$node->props['image_box_shadow_bottom'] === true) {
                $node->props['image_box_decoration'] = 'shadow';
            }

        },

    ],

];
