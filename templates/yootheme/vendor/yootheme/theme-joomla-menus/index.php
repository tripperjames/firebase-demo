<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;

$config = [

    'name' => 'yootheme/joomla-menus',

    'main' => function () {

        $this['menus'] = function () {

            return array_map(function ($menu) {
                return [
                    'id' => $menu->value,
                    'name' => $menu->text,
                ];
            }, JHtmlMenu::menus());

        };

        $this['items'] = function () {

            return array_values(array_map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'level' => $item->level > 1 ? 1 : 0,
                    'menu' => $item->menutype,
                    'parent' => $item->parent_id,
                ];
            }, AbstractMenu::getInstance('site')->getMenu()));

        };

    },

    'inject' => [
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',
    ],

    'routes' => function ($routes) {

        $routes->get('/items', function ($response) {
            return $response->withJson($this->items);
        });

    },

    'events' => [

        'theme.admin' => function ($theme) {

            $user = Factory::getUser();

            // add assets
            $this->scripts->add('customizer-menus', "{$this->path}/app/menus.min.js", 'customizer');

            // add data
            $this->customizer->addData('menu', [
                'menus' => $this->menus,
                'items' => $this->items,
                'positions' => $theme->options['menus'],
                'canDelete' => $user->authorise('core.edit.state', 'com_menus'),
                'canEdit' => $user->authorise('core.edit', 'com_menus'),
                'canCreate' => $user->authorise('core.create', 'com_menus'),
            ]);
        },

        'modules.load' => function (&$modules) {

            if ($this->app['admin']) {
                return;
            }

            // create menu modules when assigned in theme settings
            foreach ($this->theme->get('menu.positions') as $position => $menu) {

                if (!$menu) {
                    continue;
                }

                array_unshift($modules, (object) [
                    'id' => 0,
                    'name' => 'menu',
                    'module' => 'mod_menu',
                    'title' => '',
                    'showtitle' => 0,
                    'position' => $position,
                    'params' => json_encode(['menutype' => $menu, 'showAllChildren' => true]),
                ]);

            }

            $mods = [];

            foreach ($modules as $id => $module) {

                $mods[] = $module;

                if ($module->module != 'mod_menu' || $module->position != 'navbar') {
                    continue;
                }

                $params = is_string($module->params) ? json_decode($module->params, true) : $module->params;
                $params['split'] = true;
                $module->params = json_encode($params);

                $clone = clone $module;
                $clone->id = '';
                $clone->position = 'navbar-split';
                $mods[] = $clone;

            }

            $modules = $mods;

        },

    ],

    'config' => [

        'section' => [
            'title' => 'Menus',
            'priority' => 30,
        ],

        'fields' => [],

    ],

];

return defined('_JEXEC') ? $config : false;
