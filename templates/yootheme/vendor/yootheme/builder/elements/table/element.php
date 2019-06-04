<?php

return [

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

            if (!isset($node->props['meta_color']) && in_array(@$node->props['meta_style'], ['muted', 'primary'], true)) {
                $node->props['meta_color'] = $node->props['meta_style'];
                $node->props['meta_style'] = '';
            }

        },

    ],

];
