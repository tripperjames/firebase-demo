<?php

namespace YOOtheme\Theme;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\EventSubscriberInterface;
use YOOtheme\Module;
use YOOtheme\Util\Collection;

class Modules extends Module implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        $this['types'] = function () {

            $language = Factory::getLanguage();
            $types = $this->db->fetchAll("SELECT name, element FROM @extensions WHERE client_id = 0 AND type = 'module'");

            foreach ($types as $type) {
                $language->load("{$type['element']}.sys", JPATH_SITE, null, false, true);
                $data[$type['element']] = Text::_($type['name']);
            }

            natsort($data);

            return $data;
        };

        $this['modules'] = function () {
            return $this->db->fetchAll('SELECT id, title, module, position, ordering FROM @modules WHERE client_id = 0 AND published != -2 ORDER BY position, ordering');
        };

        $this['positions'] = function () {
            return array_values(
                array_unique(
                    array_merge(
                        array_keys($this->theme->options['positions']),
                        Factory::getDbo()->setQuery('SELECT DISTINCT(position) FROM #__modules WHERE client_id = 0 ORDER BY position')->loadColumn()
                    )
                )
            );
        };

        $app->extend('view', function ($view) {
            $view->addFunction('countModules', [$this, 'countModules']);
        });
    }

    public function onSite()
    {
        $renderer = version_compare(JVERSION, '3.8', '>=') ? 'Joomla\CMS\Document\Renderer\Html\ModulesRenderer' : 'JDocumentRendererHtmlModules';

        class_alias('YOOtheme\Theme\ModulesRenderer', $renderer);
    }

    public function onAdmin()
    {
        $user = Factory::getUser();

        // add data
        $this->customizer->addData('module', [
            'types' => $this->types,
            'modules' => $this->modules,
            'positions' => $this->positions,
            'canDelete' => $user->authorise('core.edit.state', 'com_modules'),
            'canEdit' => $user->authorise('core.edit', 'com_modules'),
            'canCreate' => $user->authorise('core.create', 'com_modules'),
        ]);

        $this->config->merge(['section' => [
            'url' => 'administrator/index.php?option='.(PluginHelper::isEnabled('system', 'advancedmodules') ? 'com_advancedmodules' : 'com_modules'),
        ]], true);

        $this->scripts->add('customizer-modules', "{$this->path}/app/modules.min.js", 'customizer');
    }

    public function onModules(&$modules)
    {
        if ($this->app['admin']) {
            return;
        }

        $this->view['sections']->add('breadcrumbs', function () {
            return ModuleHelper::renderModule($this->createModule([
                'name' => 'yoo_breadcrumbs',
                'module' => 'mod_breadcrumbs',
            ]));
        });

        if ($position = $this->theme->get('header.search')) {

            $search = $this->createModule([
                'name' => 'yoo_search',
                'module' => $this->theme->get('search_module'),
                'position' => $position,
            ]);

            array_push($modules, $search);

            $search = $this->createModule([
                'name' => 'yoo_search',
                'module' => 'mod_search',
                'position' => 'mobile',
            ]);

            array_push($modules, $search);
        }

        if ($position = $this->theme->get('header.social')) {

            $social = $this->createModule([
                'name' => 'yoo_socials',
                'module' => 'mod_custom',
                'position' => $position,
                'content' => $this->view->render('socials'),
            ]);

            strpos($position, 'left') ? array_unshift($modules, $social) : array_push($modules, $social);
        }

        $temp = $this->theme->params->get('module');

        foreach ($modules as $module) {

            if ($temp && $temp['id'] == $module->id) {
                $module->content = $temp['content'];
            }

            $params = json_decode($module->params);

            if (!isset($params->yoo_config) && isset($params->config)) {
                $params->yoo_config = $params->config;
            }

            $config = json_decode(isset($params->yoo_config) ? $params->yoo_config : '{}', true);

            $module->type = str_replace('mod_', '', $module->module);
            $module->attrs = ['id' => "module-{$module->id}", 'class' => []];
            $module->config = (new Collection($this->config['defaults']))->merge($config)->merge([
                'class' => [isset($params->moduleclass_sfx) ? $params->moduleclass_sfx : ''],
                'showtitle' => $module->showtitle,
                'title_tag' => isset($params->header_tag) ? $params->header_tag : 'h3',
                'is_list' => in_array($module->type, ['articles_archive', 'articles_categories', 'articles_latest', 'articles_popular', 'tags_popular', 'tags_similar']),
            ]);
        }
    }

    public function countModules($condition)
    {
        $document = Factory::getDocument();

        return $document->countModules($condition);
    }

    public function createModule($module)
    {
        static $id = 0;

        $module = (object) array_merge(['id' => 'tm-'.(++$id), 'title' => '', 'showtitle' => 0, 'position' => '', 'params' => '{}'], (array) $module);

        if (is_array($module->params)) {
            $module->params = json_encode($module->params);
        }

        return $module;
    }

    public function editModule($form, $data)
    {
        if (!in_array($form->getName(), ['com_modules.module', 'com_advancedmodules.module', 'com_config.modules'])) {
            return;
        }

        // don't show theme settings in builder module
        if (isset($data->module) && $data->module == 'mod_yootheme_builder') {
            return;
        }

        if (!isset($data->params['yoo_config']) && isset($data->params['config'])) {
            $data->params['yoo_config'] = $data->params['config'];
        }

        if (isset($this->app['locale'])) {
            $this->config->set('locale', $this->app['locale']);
        }

        if (isset($this->app['translator'])) {
            $this->config->set('locales', $this->app['translator']->getResources());
        }

        $this->config->set('base', $this->app->url($this->theme->path));
        $this->styles->add('module-styles', 'platforms/joomla/assets/css/admin.css');

        $this->scripts
            ->add('module-edit', "{$this->path}/app/module-edit.min.js", ['uikit', 'vue'])
            ->add('module-data', "var \$module = {$this->config};", '', 'string');

        $form->load('<form><fields name="params"><fieldset name="template" label="Template"><field name="yoo_config" type="hidden" default="{}" /></fieldset></fields></form>');
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.site' => 'onSite',
            'theme.admin' => 'onAdmin',
            'modules.load' => ['onModules', -10],
            'content.form' => 'editModule',
        ];
    }
}
