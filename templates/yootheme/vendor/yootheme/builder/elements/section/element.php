<?php

return [

    'updates' => [

        '1.18.0' => function ($node, array $params) {

            if (!isset($node->props['image_effect'])) {
                $node->props['image_effect'] = @$node->props['image_fixed'] ? 'fixed' : '';
            }

            if (!isset($node->props['vertical_align']) && in_array(@$node->props['height'], ['full', 'percent', 'section'])) {
                $node->props['vertical_align'] = 'middle';
            }

            if (@$node->props['style'] === 'video') {
                $node->props['style'] = 'default';
            }

            if (@$node->props['width'] === 0) {
                $node->props['width'] = 'default';
            } elseif (@$node->props['width'] === 2) {
                $node->props['width'] = 'small';
            } elseif (@$node->props['width'] === 3) {
                $node->props['width'] = 'expand';
            }

        },

        '1.18.10.2' => function ($node, array $params) {

            if (!empty($node->props['image']) && !empty($node->props['video'])) {
                unset($node->props['video']);
            }

        },

    ],

];
