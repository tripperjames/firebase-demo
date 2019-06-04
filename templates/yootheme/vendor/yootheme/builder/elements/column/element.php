<?php

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            /**
             * @var $parent
             */
            extract($params);

            // Width
            $breakpoints = ['s', 'm', 'l', 'xl'];
            $breakpoint = $parent->props['breakpoint'];
            $widths = $node->props['widths'];

            // Above Breakpoint
            $width = @$widths[0] ?: 'expand';
            $width = $width === 'fixed' ? $parent->props['fixed_width'] : $width;

            $node->attrs['class'][] = "uk-width-{$width}".($breakpoint ? "@{$breakpoint}" : '');

            // Intermediate Breakpoint
            if (isset($widths[1]) && $pos = array_search($breakpoint, $breakpoints)) {
                $breakpoint = $breakpoints[$pos - 1];
                $width = $node->props['widths'][1] ?: 'expand';
                $node->attrs['class'][] = "uk-width-{$width}@{$breakpoint}";
            }

            // Order
            if (end($parent->children) === $node && !empty($parent->props['order_last'])) {
                $node->attrs['class'][] = "uk-flex-first@{$breakpoint}";
            }

        },

    ],

    'updates' => [

        '1.18.10.4' => function ($node, array $params) {

            /**
             * @var $parent
             */
            extract($params);

            if (!empty($node->props['widths'])
                && @$parent->children
                && $node->props['widths'][0] === ''
                && count($parent->children) === 1
            ) {
                $node->props['widths'][0] = '1-1';
            }

        },

        '1.18.0' => function ($node, array $params) {

            /**
             * @var $parent
             */
            extract($params);

            if (!isset($node->props['vertical_align']) && @$parent->props['vertical_align'] === true) {
                $node->props['vertical_align'] = 'middle';
            }

            if (empty($node->props['widths']) && @$parent->children) {

                $index = array_search($node, $parent->children);

                $node->props['widths'] = array_map(function ($widths) use ($index) {
                    return explode(',', $widths)[$index];
                }, explode('|', @$parent->props['layout']));
            }

        },

    ],

];
