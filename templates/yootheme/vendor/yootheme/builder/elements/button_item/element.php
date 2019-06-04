<?php

return [

    'updates' => [

        '1.18.0' => function ($node, array $params) {

            if (@$node->props['link_target'] === true) {
                $node->props['link_target'] = 'blank';
            }

            if (@$node->props['button_style'] === 'muted') {
                $node->props['button_style'] = 'link-muted';
            }

        },

    ],

];
