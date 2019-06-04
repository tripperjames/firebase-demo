<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            $node->tags = [];

            // Filter tags
            if (!empty($node->props['filter'])) {

                foreach ($node->children as $child) {

                    $child->tags = [];

                    foreach (explode(',', $child->props['tags']) as $tag) {
                        if ($key = str_replace(' ', '-', trim($tag))) {
                            $child->tags[$key] = trim($tag);
                        }
                    }

                    $node->tags += $child->tags;
                }

                natsort($node->tags);
            }

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

            if (@$node->props['link_style'] === 'panel') {
                if (@$node->props['panel_style']) {
                    $node->props['link_type'] = 'element';
                } else {
                    $node->props['link_type'] = 'content';
                }
                $node->props['link_style'] = 'default';
            }

            if (isset($node->props['image_card'])) {
                $node->props['panel_card_image'] = $node->props['image_card'];
                unset($node->props['image_card']);
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

        },

        '1.18.0' => function ($node, array $params) {

            if (!isset($node->props['grid_parallax']) && @$node->props['grid_mode'] === 'parallax') {
                $node->props['grid_parallax'] = @$node->props['grid_parallax_y'];
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
