<?php

$help = [
    [
        'title' => 'Save, Cancel and Close',
        'src' => 'site/docs/yootheme-pro/customizer/joomla/videos/save-cancel-close.mp4',
        'documentation' => 'support/yootheme-pro/joomla/customizer#save,-cancel-and-close',
        'support' => 'support/search?tags=125&q=customizer%20save',
    ],
    [
        'title' => 'Contextual Help',
        'src' => 'site/docs/yootheme-pro/customizer/joomla/videos/contextual-help.mp4',
        'documentation' => 'support/yootheme-pro/joomla/customizer#contextual-help',
        'support' => 'support/search?tags=125&q=contextual%20help',
    ],
    [
        'title' => 'Device Preview Buttons',
        'src' => 'site/docs/yootheme-pro/customizer/joomla/videos/device-preview-buttons.mp4',
        'documentation' => 'support/yootheme-pro/joomla/customizer#device-preview-buttons',
        'support' => 'support/search?tags=125&q=customizer%20device%20preview',
    ],
    [
        'title' => 'Hide and Adjust Sidebar',
        'src' => 'site/docs/yootheme-pro/customizer/joomla/videos/hide-adjust-sidebar.mp4',
        'documentation' => 'support/yootheme-pro/joomla/customizer#hide-and-adjust-sidebar',
        'support' => 'support/search?tags=125&q=customizer%20hide%20sidebar',
    ],
];

return [

    'yootheme/joomla-modules' => require 'modules.php',

    'config' => [

        'menu' => [
            'positions' => [
                'navbar' => 'mainmenu',
                'mobile' => 'mainmenu',
            ],
        ],

        'mobile' => [

            'toggle' => 'left',

        ],

    ],

    'replacements' => [

        'help_layout' => $help,

        'help_settings' => $help,

        'list_match' => '$match(this.type, "articles_archive|articles_categories|articles_latest|articles_popular|tags_popular|tags_similar")',

        'platform' => 'joomla',

    ],

];
