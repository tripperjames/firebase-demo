<?php

namespace YOOtheme;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use YOOtheme\Joomla\Database;
use YOOtheme\Joomla\DateHelper;
use YOOtheme\Joomla\HttpClient;
use YOOtheme\Joomla\Option;
use YOOtheme\Joomla\UrlGenerator;
use YOOtheme\Joomla\UserProvider;

class Joomla extends Module
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.application.component.helper');

        $app->path = strtr(JPATH_ROOT, '\\', '/');

        $app['db'] = function () {
            return new Database(Factory::getDBO());
        };

        $app['url'] = function ($app) {
            return new UrlGenerator($app['uri']);
        };

        $app['csrf'] = function ($app) {
            return new CsrfProvider([$app['session'], 'getToken']);
        };

        $app['users'] = function ($app) {
            return new UserProvider($app['component'], isset($app['permissions']) ? $app['permissions'] : []);
        };

        $app['http'] = function () {
            return new HttpClient();
        };

        $app['date'] = function () {

            $date = new DateHelper();
            $date->setFormats([
                'full'   => Text::_('DATE_FORMAT_LC2'),
                'long'   => Text::_('DATE_FORMAT_LC3'),
                'medium' => Text::_('DATE_FORMAT_LC1'),
                'short'  => Text::_('DATE_FORMAT_LC4')
            ]);

            return $date;
        };

        $app['option'] = function ($app) {
            return new Option($app['db'], 'yootheme', 'system');
        };

        $app['locale'] = function () {
            return str_replace('-', '_', Factory::getLanguage()->get('tag'));
        };

        $app['admin'] = function () {
            return Factory::getApplication()->isClient('administrator');
        };

        $app['session'] = function () {
            return Factory::getApplication()->getSession();
        };

        $app['secret'] = function () {
            return Factory::getConfig()->get('secret');
        };

        $app->extend('uri', function ($uri) {
            return $uri->withBasePath(Uri::root(true));
        });

        $app->extend('request', function ($request) {

            $app = Factory::getApplication();
            $query = $request->getQueryParams();
            $inputs = ['Itemid', 'option', 'layout', 'view', 'task'];

            foreach ($inputs as $key) {
                if ($value = $app->input->get($key)) {
                    $query[$key] = $value;
                }
            }

            return $request->withQueryParams($query);
        });

        $app->on('view', [$this, 'registerAssets'], -10);
    }

    /**
     * Callback to register assets.
     */
    public function registerAssets()
    {
        $document = Factory::getDocument();

        foreach ($this->styles as $style) {

            $id = sprintf('%s-css', $style->getName());

            if ($source = $style->getSource()) {
                $document->addStyleSheet(htmlentities($this->url->to($source, ($v = $style->getOption('version')) ? ['v' => $v] : [])), 'text/css', null, compact('id'));
            } elseif ($content = $style->getContent()) {
                $document->addStyleDeclaration($content);
            }
        }

        foreach ($this->scripts as $script) {
            if ($source = $script->getSource()) {
                $document->addScript(htmlentities($this->url->to($source, ($v = $script->getOption('version')) ? ['v' => $v] : [])), 'text/javascript', $script->getOption('defer') ?: false);
            } elseif ($content = $script->getContent()) {
                $document->addScriptDeclaration($content);
            }
        }
    }
}
