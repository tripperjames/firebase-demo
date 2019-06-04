<?php

defined('_JEXEC') or die;

$aliases = [];

// class aliases for Joomla < 3.9
if (version_compare(JVERSION, '3.9', '<')) {
    $aliases['JFile'] = 'Joomla\CMS\Filesystem\File';
    $aliases['JFolder'] = 'Joomla\CMS\Filesystem\Folder';
    $aliases['JPath'] = 'Joomla\CMS\Filesystem\Path';
}

// class aliases for Joomla < 3.8
if (version_compare(JVERSION, '3.8', '<')) {
    $aliases['JAccess'] = 'Joomla\CMS\Access\Access';
    $aliases['JComponentHelper'] = 'Joomla\CMS\Component\ComponentHelper';
    $aliases['JControllerLegacy'] = 'Joomla\CMS\MVC\Controller\BaseController';
    $aliases['JDate'] = 'Joomla\CMS\Date\Date';
    $aliases['JDocumentRenderer'] = 'Joomla\CMS\Document\DocumentRenderer';
    $aliases['JEditor'] = 'Joomla\CMS\Editor\Editor';
    $aliases['JFactory'] = 'Joomla\CMS\Factory';
    $aliases['JHelperMedia'] = 'Joomla\CMS\Helper\MediaHelper';
    $aliases['JHelperRoute'] = 'Joomla\CMS\Helper\RouteHelper';
    $aliases['JHelperTags'] = 'Joomla\CMS\Helper\TagsHelper';
    $aliases['JHtml'] = 'Joomla\CMS\HTML\HTMLHelper';
    $aliases['JHttpFactory'] = 'Joomla\CMS\Http\HttpFactory';
    $aliases['JLanguageMultilang'] = 'Joomla\CMS\Language\Multilanguage';
    $aliases['JLayoutHelper'] = 'Joomla\CMS\Layout\LayoutHelper';
    $aliases['JMenu'] = 'Joomla\CMS\Menu\AbstractMenu';
    $aliases['JModelLegacy'] = 'Joomla\CMS\MVC\Model\BaseDatabaseModel';
    $aliases['JModuleHelper'] = 'Joomla\CMS\Helper\ModuleHelper';
    $aliases['JPlugin'] = 'Joomla\CMS\Plugin\CMSPlugin';
    $aliases['JPluginHelper'] = 'Joomla\CMS\Plugin\PluginHelper';
    $aliases['JRoute'] = 'Joomla\CMS\Router\Route';
    $aliases['JRouter'] = 'Joomla\CMS\Router\Router';
    $aliases['JSession'] = 'Joomla\CMS\Session\Session';
    $aliases['JText'] = 'Joomla\CMS\Language\Text';
    $aliases['JUri'] = 'Joomla\CMS\Uri\Uri';
}

// creates class aliases
foreach ($aliases as $original => $alias) {
    JLoader::registerAlias($alias, $original);
}
