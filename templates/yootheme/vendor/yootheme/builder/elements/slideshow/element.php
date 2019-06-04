<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            // TODO Fix me
            $node->props['parallaxOptions'] = $node->props['overlay_animation'] === 'parallax'
                ? [$params['view'], 'parallaxOptions']
                : function () { return false; };

        },

    ],

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

            if (isset($node->props['thumbnav_inline_svg'])) {
                $node->props['thumbnav_svg_inline'] = $node->props['thumbnav_inline_svg'];
                unset($node->props['thumbnav_inline_svg']);
            }

        },

        '1.18.0' => function ($node, array $params) {

            if (!isset($node->props['slideshow_box_decoration']) && @$node->props['slideshow_box_shadow_bottom'] === true) {
                $node->props['slideshow_box_decoration'] = 'shadow';
            }

            if (!isset($node->props['meta_color']) && @$node->props['meta_style'] === 'muted') {
                $node->props['meta_color'] = 'muted';
                $node->props['meta_style'] = '';
            }

        },

    ],

];
