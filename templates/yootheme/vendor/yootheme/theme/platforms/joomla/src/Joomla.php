<?php

namespace YOOtheme\Theme;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use YOOtheme\EventSubscriberInterface;
use YOOtheme\Module;
use YOOtheme\Theme\Joomla\ChildThemeListener;
use YOOtheme\Theme\Joomla\CustomizerListener;
use YOOtheme\Theme\Joomla\SystemCheck;
use YOOtheme\Theme\Joomla\UrlListener;
use YOOtheme\Util\Str;

class Joomla extends Module implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        $app->subscribe(new CustomizerListener())
            ->subscribe(new ChildThemeListener())
            ->subscribe(new UrlListener());

        $app['locator']->addPath("{$this->path}/assets", 'assets');

        $app['systemcheck'] = function () {
            return new SystemCheck();
        };

        $app['trans'] = $app->protect(function ($id) {
            return Text::_($id);
        });

        $app['apikey'] = function () {

            $plugin = PluginHelper::getPlugin('installer', 'yootheme');

            return $plugin ? (new Registry($plugin->params))->get('apikey') : false;
        };

     }

    public function onInit($theme)
    {
        $language = Factory::getLanguage();
        $document = Factory::getDocument();

        $language->load('tpl_yootheme', $theme->path);
        $document->setBase(htmlspecialchars(Uri::current()));

        $this->url->addResolver(function ($path, $parameters, $secure, $next) {

            $uri = $next($path, $parameters, $secure, $next);

            if (Str::startsWith($uri->getQueryParam('p'), 'theme/')) {

                $query = $uri->getQueryParams();
                $query['option'] = 'com_ajax';
                $query['style'] = $this->theme->id;

                $uri = $uri->withQueryParams($query);
            }

            return $uri;
        });

        if (!$this->app['admin'] && !$theme->isCustomizer && $document->getType() == 'html') {
            $this->app->trigger('theme.site', [$theme]);
        }
    }

    public function onSite($theme)
    {
        require "{$theme->path}/html/helpers.php";

        $app = Factory::getApplication();
        $config = Factory::getConfig();
        $document = Factory::getDocument();

        $theme->set('direction', $document->direction);
        $theme->set('site_url', rtrim(Uri::root(), '/'));
        $theme->set('page_class', $app->getParams()->get('pageclass_sfx'));

        if ($this->customizer->isActive()) {
            HTMLHelper::_('behavior.keepalive');
            $config->set('caching', 0);
        }
    }

    public function onContentData($context, $data)
    {
        if ($context != 'com_templates.style') {
            return;
        }

        $this->scripts->add('$customizer-data', sprintf('var $customizer = %s;', json_encode([
            'context' => $context,
            'apikey' => $this->app['apikey'],
            'url' => $this->app->url(($this->app['admin'] ? 'administrator/' : '').'index.php?p=customizer&option=com_ajax', ['style' => $data->id]),
        ])), [], 'string');
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', -15],
            'theme.site' => ['onSite', 10],
            'content.data' => 'onContentData',
        ];
    }
}
