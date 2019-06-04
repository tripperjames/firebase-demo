<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use YOOtheme\EventSubscriber;
use YOOtheme\Theme\Customizer;

class CustomizerListener extends EventSubscriber
{
    protected $cookie;

    protected $inject = [
        'db' => 'app.db',
        'url' => 'app.url',
        'apikey' => 'app.apikey',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',
    ];

    public function onInit($theme)
    {
        $app = Factory::getApplication();
        $session = Factory::getSession();

        $this->cookie = hash_hmac('md5', $theme->template, $this->app['secret']);
        $theme->isCustomizer = $app->input->get('p') == 'customizer';

        $active = $theme->isCustomizer || $app->input->cookie->get($this->cookie);

        // override params
        if ($active) {

            $custom = $app->input->getBase64('customizer');
            $params = $session->get($this->cookie) ?: [];

            foreach ($params as $key => $value) {
                $theme->params->set($key, $value);
            }

            if ($custom && $data = json_decode(base64_decode($custom), true)) {

                foreach ($data as $key => $value) {

                    if (in_array($key, ['config', 'admin', 'user_id'])) {
                        $params[$key] = $value;
                    }

                    $theme->params->set($key, $value);
                }

                $session->set($this->cookie, $params);
            }

        }

        $theme['customizer'] = function () use ($active) {
            return new Customizer($active);
        };
    }

    public function onSave(&$config)
    {
        $user = Factory::getUser();
        $plugin = PluginHelper::getPlugin('installer', 'yootheme');

        if (isset($config['yootheme_apikey'])) {

            if ($plugin && $user->authorise('core.admin', 'com_plugins')) {
                $reg = new Registry($plugin->params);
                $reg->set('apikey', $config['yootheme_apikey']);
                $this->db->executeQuery("UPDATE @extensions SET params = :params WHERE element = 'yootheme' AND folder = 'installer'", ['params' => $reg->toString()]);
            }

            unset($config['yootheme_apikey']);
         }
    }

    public function onSite($theme)
    {
        // is active?
        if (!$this->customizer->isActive()) {
            return;
        }

        // add assets
        $this->styles->add('customizer', 'platforms/joomla/assets/css/site.css');

        // add data
        $this->customizer->addData('id', $theme->id);
    }

    public function onAdmin($theme)
    {
        $user = Factory::getUser();

        // add assets
        $this->styles->add('customizer', 'platforms/joomla/assets/css/admin.css');
        $this->scripts->add('customizer', 'platforms/joomla/app/customizer.min.js', ['uikit', 'commons', 'app-config']);

        // add data
        $this->customizer->mergeData([
            'id' => $theme->id,
            'cookie' => $this->cookie,
            'template' => basename($theme->path),
            'site' => $this->url->base().'/index.php',
            'root' => Uri::base(true),
            'token' => Session::getFormToken(),
            'config' => [
                'yootheme_apikey' => $this->apikey,
            ],
            'admin' => $this->app['admin'],
            'user_id' => $user->id,
        ]);
    }

    public function onView($event)
    {
        $app = Factory::getApplication();

        // add data
        if ($app->get('themeFile') != 'offline.php' && $this->customizer->isActive() && $data = $this->customizer->getData()) {
            $this->scripts->add('customizer-data', sprintf('var $customizer = %s;', json_encode($data)), 'customizer', 'string');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', 10],
            'theme.site' => ['onSite', -5],
            'theme.admin' => 'onAdmin',
            'theme.save' => 'onSave',
            'view' => 'onView',
        ];
    }
}
