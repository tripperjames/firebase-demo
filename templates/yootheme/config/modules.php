<?php

return [

    'fields' => [

        'visibility' => [
            'label' => 'Visibility',
            'description' => 'Display the module only from this device width and larger.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Always' => '',
                'Small (Phone Landscape)' => 's',
                'Medium (Tablet Landscape)' => 'm',
                'Large (Desktop)' => 'l',
                'X-Large (Large Screens)' => 'xl',
            ],
        ],

        'style' => [
            'label' => 'Style',
            'description' => 'The module\'s style.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Blank' => '',
                'Card Default' => 'card-default',
                'Card Primary' => 'card-primary',
                'Card Secondary' => 'card-secondary',
                'Card Hover' => 'card-hover',
            ],
            'show' => '!$match(this.position, "^(toolbar-left|toolbar-right|navbar|header|mobile|debug)$")',
        ],

        'title_style' => [
            'label' => 'Title Style',
            'description' => 'Title styles differ in font-size but may also come with a predefined color, size and font.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Default' => '',
                'Primary' => 'heading-primary',
                'H1' => 'h1',
                'H2' => 'h2',
                'H3' => 'h3',
                'H4' => 'h4',
                'H5' => 'h5',
                'H6' => 'h6',
            ],
            'show' => '!$match(this.position, "^(toolbar-left|toolbar-right|navbar|header|debug)$")',
        ],

        'title_decoration' => [
            'label' => 'Title Decoration',
            'description' => 'Decorate the title with a divider, bullet or a line that is vertically centered to the heading.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'None' => '',
                'Divider' => 'divider',
                'Bullet' => 'bullet',
                'Line' => 'line',
            ],
            'show' => '!$match(this.position, "^(toolbar-left|toolbar-right|navbar|header|debug)$")',
        ],

        'text_align' => [
            'label' => 'Alignment',
            'description' => 'Center, left and right alignment may depend on a breakpoint and require a fallback.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Inherit' => '',
                'Left' => 'left',
                'Center' => 'center',
                'Right' => 'right',
                'Justify' => 'justify',
            ],
            'show' => '!$match(this.position, "^(toolbar-left|toolbar-right|navbar|header|debug)$")',
        ],

        'text_align_breakpoint' => [
            'label' => 'Alignment Breakpoint',
            'description' => 'Define the device width from which the alignment will apply.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Always' => '',
                'Small (Phone Landscape)' => 's',
                'Medium (Tablet Landscape)' => 'm',
                'Large (Desktop)' => 'l',
                'X-Large (Large Screens)' => 'xl',
            ],
            'show' => '$match(this.position, "^(top|bottom|sidebar|mobile|builder-\d+)$") && text_align && text_align != "justify"',
        ],

        'text_align_fallback' => [
            'label' => 'Alignment Fallback',
            'description' => 'Define an alignment fallback for device widths below the breakpoint.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Inherit' => '',
                'Left' => 'left',
                'Center' => 'center',
                'Right' => 'right',
                'Justify' => 'justify',
            ],
            'show' => '$match(this.position, "^(top|bottom|sidebar|mobile|builder-\d+)$") && text_align && text_align != "justify" && text_align_breakpoint',
        ],

        'width' => [
            'label' => 'Width',
            'description' => 'The width of the grid column that contains the module.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Expand' => '',
                '20%' => '1-5',
                '25%' => '1-4',
                '33%' => '1-3',
                '40%' => '2-5',
                '50%' => '1-2',
                '100%' => '1-1',
            ],
            'show' => '$match(this.position, "^(top|bottom|builder-\d+)$")',
        ],

        'maxwidth' => [
            'label' => 'Max Width',
            'description' => 'The module\'s maximum width.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'None' => '',
                'Small' => 'small',
                'Medium' => 'medium',
                'Large' => 'large',
                'X-Large' => 'xlarge',
                'XX-Large' => 'xxlarge',
            ],
            'show' => '$match(this.position, "^(top|bottom|builder-\d+)$")',
        ],

        'maxwidth_align' => [
            'label' => 'Max Width (Alignment)',
            'description' => 'Set how the module should align when the container is larger than its max-width.',
            'type' => 'checkbox',
            'text' => 'Center the module',
            'show' => 'maxwidth != "" && $match(this.position, "^(top|bottom|builder-\d+)$")',
        ],

        'list_style' => [
            'label' => 'List Style',
            'description' => 'Select the list style.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Default' => '',
                'Divider' => 'divider',
            ],
            'show' => '{list_match}',
        ],

        'link_style' => [
            'label' => 'Link Style',
            'description' => 'Select the link style.',
            'type' => 'select',
            'default' => '',
            'options' => [
                'Default' => '',
                'Muted' => 'muted',
            ],
            'show' => '{list_match}',
        ],

        'menu_style' => [
            'label' => 'Menu Style',
            'description' => 'Select the menu style',
            'type' => 'select',
            'default' => 'nav',
            'options' => [
                'Nav' => 'nav',
                'Subnav' => 'subnav',
            ],
            'show' => '$match(this.type, "menu") && !$match(this.position, "toolbar-left|toolbar-right|navbar|mobile")',
        ],

    ],

];
