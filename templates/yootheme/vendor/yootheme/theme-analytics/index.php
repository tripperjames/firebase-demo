<?php

return [

    'name' => 'yootheme/theme-analytics',

    'inject' => [
        'scripts' => 'app.scripts',
    ],

    'events' => [

        'theme.site' => function ($theme) {

            $keys = [
                'google_analytics',
                'google_analytics_anonymize',
            ];

            if ($theme->get($keys[0])) {

                foreach ($keys as $key) {
                    $theme->data->set($key, $theme->get($key));
                }

                $this->scripts->add('analytics', "{$this->path}/app/analytics.min.js", 'theme-script', ['defer' => true]);
            }

        },

    ],

];
