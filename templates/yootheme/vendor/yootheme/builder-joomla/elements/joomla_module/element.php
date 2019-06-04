<?php

use Joomla\CMS\Helper\ModuleHelper;

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            $load = new ReflectionMethod('Joomla\CMS\Helper\ModuleHelper', 'load');
            $load->setAccessible(true);

            foreach ($load->invoke(null) as $module) {

                if ($node->props['module'] !== $module->id) {
                    continue;
                }

                $node->props = $module->config->merge($node->props, true)->all();
                $node->module = (object) [
                    'title' => $module->title,
                    'content' => ModuleHelper::renderModule($module),
                ];

                break;
            }

            // return false, if no module content was found
            if (empty($node->props['module']) || empty($node->module->content)) {
                return false;
            }

        },

    ],

];
