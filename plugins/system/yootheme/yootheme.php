<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;
use Joomla\Registry\Registry;
use YOOtheme\Module\ConfigLoader;
use YOOtheme\Theme;

// include classmap
include __DIR__ . '/classmap.php';

class plgSystemYOOtheme extends CMSPlugin
{
    protected $db;
    protected $cms;
    protected $app = array();
    protected $root = JPATH_ROOT;
    protected $loaded = false;

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        $this->cms = Factory::getApplication();
    }

    public function __sleep()
    {
        return array(); // override serialize for debug logger
    }

    public function onAfterRoute()
    {
        if ($response = $this->handleRequest()) {

            if ($response->getStatusCode() == 403 && !strpos($response->getContentType(), 'json') && Factory::getUser()->guest) {

                if ($this->cms->isClient('administrator')) {
                    return;
                }

                $response = $response->withRedirect(Route::_('index.php?option=com_users&view=login', false));
            }

            $response->send();

            $this->cms->triggerEvent('onAfterRespond');

            exit;
        }
    }

    public function onAfterDispatch()
    {
        if (isset($this->app['events'])) {
            $this->app['events']->trigger('dispatch', array($this->cms->getDocument(), $this->cms->input));
        }
    }

    public function onBeforeRender()
    {
        if (isset($this->app['events'])) {
            $this->app['events']->trigger('content', array($this->cms->getDocument()->getBuffer('component')));
        }
    }

    public function onBeforeCompileHead()
    {
        if (isset($this->app['events'])) {
            $this->app['events']->trigger('view', array($this->app));
        }
    }

    public function onAfterCleanModuleList(&$modules)
    {
        if (isset($this->app['events'])) {
            $this->app['events']->trigger('modules.load', array(&$modules));
        }
    }

    public function onContentPrepareForm($form, $data)
    {
        if (isset($this->app['events'])) {
            $this->app['events']->trigger('content.form', array($form, (object) $data));
        }
    }

    public function onContentPrepareData($context, $data)
    {
        if (isset($this->app['events'])) {
            $this->app['events']->trigger('content.data', array($context, (object) $data));
        }
    }

    public function onContentBeforeSave($context, $article)
    {
        if (isset($this->app['events'])) {
            $this->app['events']->trigger('content.beforeSave', array($context, $article, $this->cms->input));
        }
    }

    public function onGetIcons($context)
    {
        if ($context != 'mod_quickicon' || !Factory::getUser()->authorise('core.edit', 'com_templates')) {
            return;
        }

        if (!$templ = $this->loadTemplateStyle() or !$templ->params->get('yootheme')) {
            return;
        }

        return array(array(
            'image' => 'star',
            'text'  => 'YOOtheme',
            'link'  => "index.php?option=com_ajax&p=customizer&style={$templ->id}",
        ));
    }

    protected function handleRequest()
    {
        $path = $this->cms->input->get('p');
        $option = $this->cms->input->getCmd('option');
        $style =  $this->cms->input->getInt($option == 'com_templates' ? 'id' : 'style');
        $active = $this->cms->isClient('administrator') && in_array($option, array('com_modules', 'com_advancedmodules', 'com_content', 'com_templates'), true);
        $templ = ($path && $option == 'com_ajax' || $active) ? $this->loadTemplateStyle($style) : $this->cms->getTemplate(true);

        if ($templ && $templ->params->get('yootheme') && $this->loadTemplate($templ)) {
            return $path && $option == 'com_ajax' ? $this->app->run(false) : null;
        }
    }

    protected function loadTemplate($templ)
    {
        if ($this->loaded) {
            return;
        }

        $this->loaded = true;

        $templ->params->set('config', json_decode($templ->params->get('config', '{}'), true));

        $template = "{$this->root}/templates/{$templ->template}/config.php";
        $bootstrap = "{$this->root}/templates/{$templ->template}/vendor/yootheme/theme/bootstrap.php";

        if (($app = require $bootstrap) && is_array($config = require $template)) {

            $app->addLoader(new ConfigLoader($config));
            $app->addLoader(function ($options, $next) use ($templ) {

                $module = $next($options);

                if ($module instanceof Theme) {

                    $module->id = $templ->id;
                    $module->title = @$templ->title;
                    $module->default = $templ->home == 1;
                    $module->params = $templ->params;
                    $module->template = $templ->template;

                    HTMLHelper::register('theme', function () use ($module) {
                        return $module;
                    });
                }

                return $module;
            });

            $app->load('config.php', dirname($template));

            if ($child = $templ->params->get('config.child_theme')) {
                $app->load('config.php', "{$this->root}/templates/{$templ->template}_{$child}");
            }

            $app->init();

            return $this->app = $app;
        }
    }

    protected function loadTemplateStyle($id = 0)
    {
        $query = "SELECT * FROM #__template_styles WHERE " . ($id ? "id = {$id}" : "client_id = 0 AND home = '1'");

        if ($templ = $this->db->setQuery($query)->loadObject()) {
            $templ->params = new Registry($templ->params);
            return $templ;
        }
    }
}
