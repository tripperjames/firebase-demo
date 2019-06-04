<?php

namespace YOOtheme\Theme;

use Joomla\CMS\Factory;
use Joomla\CMS\Document\DocumentRenderer;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use YOOtheme\Util\Collection;

class ModulesRenderer extends DocumentRenderer
{
    public function render($position, $params = [], $content = null)
    {
        $app = Factory::getApplication();
        $user = Factory::getUser();

        $modules = ModuleHelper::getModules($position);
        $renderer = $this->_doc->loadRenderer('module');

        $frontEdit = $app->isSite() && $app->get('frontediting', 1) && !$user->guest;
        $menusEdit = $app->get('frontediting', 1) == 2 && $user->authorise('core.edit', 'com_menus');

        foreach ($modules as $module) {

            $moduleHtml = $renderer->render($module, $params, $content);

            if (!isset($module->attrs)) {
                $module->attrs = [];
                $module->config = new Collection();
            }

            if ($frontEdit && trim($moduleHtml) != '' && $user->authorise('module.edit.frontend', 'com_modules.module.'.$module->id)) {
                $displayData = ['moduleHtml' => &$moduleHtml, 'module' => $module, 'position' => $position, 'menusediting' => $menusEdit];
                LayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
            }

            $module->content = $moduleHtml;
        }

        return HTMLHelper::_('render', 'position', array_merge(['items' => $modules], $params));
    }
}
