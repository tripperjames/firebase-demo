<?php

return [

    'updates' => [

        '1.19.0-beta.0.1' => function ($node, array $params) {

            if (@$node->props['meta_align'] === 'top') {
                $node->props['meta_align'] = 'above-title';
            }
    
            if (@$node->props['meta_align'] === 'bottom') {
                $node->props['meta_align'] = 'below-title';
            }

        },

        '1.18.10.3' => function ($node, array $params) {

            if (@$node->props['meta_align'] === 'top') {
                if (!empty($node->props['meta_margin'])) {
                    $node->props['title_margin'] = $node->props['meta_margin'];
                }
                $node->props['meta_margin'] = '';
            }

        },

        '1.18.10.1' => function ($node, array $params) {

            if (isset($node->props['image_inline_svg'])) {
                $node->props['image_svg_inline'] = $node->props['image_inline_svg'];
                unset($node->props['image_inline_svg']);
            }

            if (isset($node->props['image_animate_svg'])) {
                $node->props['image_svg_animate'] = $node->props['image_animate_svg'];
                unset($node->props['image_animate_svg']);
            }

            if (isset($node->props['switcher_thumbnail_inline_svg'])) {
                $node->props['switcher_thumbnail_svg_inline'] = $node->props['switcher_thumbnail_inline_svg'];
                unset($node->props['switcher_thumbnail_inline_svg']);
            }

        },

        '1.18.0' => function ($node, array $params) {

            if (@$node->props['switcher_style'] === 'thumbnail') {
                $node->props['switcher_style'] = 'thumbnav';
            }

            if (!isset($node->props['image_box_decoration']) && @$node->props['image_box_shadow_bottom'] === true) {
                $node->props['image_box_decoration'] = 'shadow';
            }

            if (!isset($node->props['meta_color']) && @$node->props['meta_style'] === 'muted') {
                $node->props['meta_color'] = 'muted';
                $node->props['meta_style'] = '';
            }

        },

    ],

];
