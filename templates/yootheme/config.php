<?php

return array_merge([

    'name' => 'YOOtheme',

    'main' => 'YOOtheme\\Theme',

    'version' => '1.19.0',

    'require' => 'yootheme/theme',

    'include' => 'vendor/yootheme/theme/index.php',

    'inject' => [

        'view' => 'app.view',
        'image' => 'app.image',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
        'locator' => 'app.locator',
        'modules' => 'app.modules',
        'builder' => 'app.builder',

    ],

    'menus' => [

        'navbar' => 'Navbar',
        'mobile' => 'Mobile',

    ],

    'positions' => [

        'toolbar-left' => 'Toolbar Left',
        'toolbar-right' => 'Toolbar Right',
        'navbar' => 'Navbar',
        'header' => 'Header',
        'top' => 'Top',
        'sidebar' => 'Sidebar',
        'bottom' => 'Bottom',
        'mobile' => 'Mobile',
        'builder-1' => 'Builder 1',
        'builder-2' => 'Builder 2',
        'builder-3' => 'Builder 3',
        'builder-4' => 'Builder 4',
        'builder-5' => 'Builder 5',
        'builder-6' => 'Builder 6',

    ],

    'styles' => [

        'imports' => [
            'vendor/assets/uikit/src/images/backgrounds/*.svg',
            'vendor/assets/uikit-themes/*/images/*.svg',
        ],

    ],

    'config' => [

        'menu' => [
            'positions' => [
                'navbar' => '',
                'mobile' => '',
            ],
        ],

    ],

    'events' => [

        'theme.init' => [function () {

            // Deprecated
            if ($this->get('header.layout') == 'toggle-offcanvas') {
                $this->set('header.layout', 'offcanvas-top-a');
            }

            // Deprecated
            if ($this->get('header.layout') == 'toggle-modal') {
                $this->set('header.layout', 'modal-center-a');
                $this->set('navbar.toggle_menu_style', 'primary');
                $this->set('navbar.toggle_menu_center', true);
            }

            // Deprecated
            if ($this->get('mobile.animation') == 'modal' && !$this->has('mobile.menu_center')) {
                $this->set('mobile.menu_style', 'primary');
                $this->set('mobile.menu_center', true);
                $this->set('mobile.menu_center_vertical', true);
            }

        }, -10],

        'theme.site' => function () {

            $rtl = $this->get('direction') == 'rtl' ? '{.rtl,}' : '';
            $style = $this->locator->find("@theme/css/theme{.{$this->id},}{$rtl}.css");

            $this->styles->add('theme-style', $style, 'highlight', [
                'version' => $css = filemtime($style),
            ]);

            if (filemtime(__FILE__) >= $css) {
                $this->styles->add('theme-style-update', 'css/theme.update.css');
            }

            $this->scripts
                ->add('theme-uikit', 'vendor/assets/uikit/dist/js/uikit'.(!$this->debug ? '.min' : '').'.js')
                ->add('theme-uikit-icons', 'vendor/assets/uikit/dist/js/uikit-icons{-'.explode(':', $this->get('style', ''))[0].',}.min.js', 'theme-uikit')
                ->add('theme-script', 'js/theme.js', 'theme-uikit-icons')
                ->add('theme-data', sprintf('var $theme = %s;', json_encode($this->data)), [], 'string');

            if ($custom = $this->locator->find('@assets/js/custom.js')) {
                $this->scripts->add('theme-custom', $custom, 'theme-script');
            }

            if ($custom = $this->locator->find('@assets/css/custom.css')) {
                $this->styles->add('theme-custom', $custom, 'theme-style');
            }

        },

        'content' => function ($content) {

            if ($style = $this->get('highlight') and strpos($content, '</code>')) {
                $this->styles->add('highlight', "vendor/assets/highlightjs/styles/{$style}.css", '', ['defer' => true]);
                $this->scripts
                    ->add('highlight', 'vendor/assets/highlightjs/highlight.min.js', 'theme-script', ['defer' => true])
                    ->add('highlight-init', 'UIkit.util.ready(function() {hljs.initHighlightingOnLoad()});', 'highlight', ['type' => 'string', 'defer' => true]);
            }

        },

    ],

    'yootheme/layout' => require 'config/layout.php',
    'yootheme/settings' => require 'config/settings.php',
    'yootheme/styler' => require 'config/styler.php',

], require 'config/platform.php');

