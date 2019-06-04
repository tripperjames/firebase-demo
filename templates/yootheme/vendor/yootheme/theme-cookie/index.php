<?php

return [

    'name' => 'yootheme/theme-cookie',

    'inject' => [

        'view' => 'app.view',
        'scripts' => 'app.scripts',
        'request' => 'app.request',
        'customizer' => 'theme.customizer',

    ],

    'events' => [

        'theme.init' => function ($theme) {

            // Deprecated
            if ($theme->get('cookie.mode') === null && $theme->get('cookie.active')) {
                $theme->set('cookie.mode', 'notification');
            }
            if ($theme->get('cookie.button_consent_style') === null) {
                $theme->set('cookie.button_consent_style', $theme->get('cookie.button_style'));
            }

            // set defaults
            $theme->merge($this->config['defaults'], true);
        },

        'theme.site' => function ($theme) {

            if (!$theme->get('cookie.mode')) {
                return;
            }

            $theme->data->merge([
                'cookie' => [
                    'mode' => $theme->get('cookie.mode'),
                    'template' => trim($this->view->render('cookie')),
                ]
            ]);

            if (!$this->customizer->isActive()) {

                if ($custom = $theme->get('cookie.custom_js')) {
                    $this->scripts->add('cookie-custom_js', "(window.\$load = window.\$load || []).push(function(c,n) {try {{$custom}} catch (e) {console.error(e)} n()});\n", 'theme-data', 'string');
                }

                $this->scripts->add('cookie', "{$this->path}/app/cookie.min.js", 'theme-script', ['defer' => true]);
            }

        },

        'theme.admin' => function ($theme) {
            $this->scripts->add('cookie-panel', "{$this->path}/app/cookie-panel.min.js", 'customizer');
        },

    ],

    'config' => [

        'panels' => [

            'cookie' => [

                'title' => 'Cookie Banner',
                'width' => 400,
                'fields' => [

                    'cookie.mode' => [
                        'label' => 'Cookie Banner',
                        'description' => 'Show a banner to inform your visitors of cookies used by your website. Choose between a simple notification that cookies are loaded or require a mandatory consent before loading cookies.',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Disabled' => '',
                            'As notification only ' => 'notification',
                            'With mandatory consent' => 'consent',
                        ],
                    ],

                    'cookie.type' => [
                        'label' => 'Type',
                        'description' => 'Choose between an attached bar or a notification.',
                        'type' => 'select',
                        'options' => [
                            'Bar' => 'bar',
                            'Notification' => 'notification',
                        ],
                        'enable' => 'cookie.mode',
                    ],

                    'cookie.bar_position' => [
                        'label' => 'Position',
                        'description' => 'The bar at the top pushes the content down while the bar at the bottom is fixed above the content.',
                        'type' => 'select',
                        'options' => [
                            'Top' => 'top',
                            'Bottom ' => 'bottom',
                        ],
                        'show' => 'cookie.type == "bar"',
                        'enable' => 'cookie.mode',
                    ],

                    'cookie.bar_style' => [
                        'label' => 'Style',
                        'type' => 'select',
                        'options' => [
                            'Default' => 'default',
                            'Muted ' => 'muted',
                            'Primary' => 'primary',
                            'Secondary ' => 'secondary',
                        ],
                        'show' => 'cookie.type == "bar"',
                        'enable' => 'cookie.mode',
                    ],

                    'cookie.notification_position' => [
                        'label' => 'Position',
                        'type' => 'select',
                        'options' => [
                            'Bottom Left' => 'bottom-left',
                            'Bottom Center' => 'bottom-center',
                            'Bottom Right' => 'bottom-right',
                        ],
                        'show' => 'cookie.type == "notification"',
                        'enable' => 'cookie.mode',
                    ],

                    'cookie.notification_style' => [
                        'label' => 'Style',
                        'type' => 'select',
                        'default' => '',
                        'options' => [
                            'Default' => '',
                            'Primary' => 'primary',
                            'Warning ' => 'warning',
                            'Danger ' => 'danger',
                        ],
                        'show' => 'cookie.type == "notification"',
                        'enable' => 'cookie.mode',
                    ],

                    'cookie.message' => [
                        'label' => 'Content',
                        'description' => 'Enter the cookie consent message. The default text serves as illustration. Please adjust it according to the cookie laws of your country.',
                        'type' => 'editor',
                        'editor' => 'visual',
                        'enable' => 'cookie.mode',
                    ],

                    'cookie.button_consent_style' => [
                        'label' => 'Consent Button Style',
                        'type' => 'select',
                        'options' => [
                            'Close Icon' => 'icon',
                            'Button Default' => 'default',
                            'Button Primary' => 'primary',
                            'Button Secondary' => 'secondary',
                            'Button Text' => 'text',
                        ],
                        'enable' => 'cookie.mode',
                    ],

                    'cookie.button_consent_text' => [
                        'label' => 'Consent Button Text',
                        'description' => 'Enter the text for the button.',
                        'enable' => 'cookie.mode && cookie.button_consent_style != "icon"',
                    ],

                    'cookie.button_reject_style' => [
                        'label' => 'Reject Button Style',
                        'type' => 'select',
                        'options' => [
                            'Button Default' => 'default',
                            'Button Primary' => 'primary',
                            'Button Secondary' => 'secondary',
                            'Button Text' => 'text',
                        ],
                        'enable' => 'cookie.mode && cookie.mode == "consent"',
                    ],

                    'cookie.button_reject_text' => [
                        'label' => 'Reject Button Text',
                        'description' => 'Enter the text for the button.',
                        'enable' => 'cookie.mode && cookie.mode == "consent"',
                    ],

                    'cookie.custom_js' => [
                        'label' => 'Cookie Scripts',
                        'description' => 'Add custom JavaScript which sets cookies. It will be first loaded after consent is given. The &lt;script&gt; tag is not needed.',
                        'type' => 'editor',
                        'editor' => 'code',
                        'mode' => 'javascript',
                        'enable' => 'cookie.mode && cookie.mode == "consent"',
                    ],
                ],

            ],

        ],

        'defaults' => [

            'cookie' => [

                'type' => 'bar',
                'bar_position' => 'bottom',
                'bar_style' => 'muted',
                'notification_position' => 'bottom-center',
                'message' => 'By using this website, you agree to the use of cookies as described in our Privacy Policy.',
                'button_consent_style' => 'icon',
                'button_consent_text' => 'Ok',
                'button_reject_style' => 'default',
                'button_reject_text' => 'No, Thanks',

            ],

        ],

    ],

];
