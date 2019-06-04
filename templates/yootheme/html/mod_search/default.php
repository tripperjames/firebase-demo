<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

echo HTMLHelper::_('render', 'search', [

    'position' => $module->position,
    'attrs' => [

        'id' => "search-{$module->id}",
        'action' => Route::_('index.php'),
        'method' => 'post',
        'role' => 'search',
        'class' => ($class = $params->get('moduleclass_sfx')) ? [$class] : '',

    ],
    'fields' => [

        ['tag' => 'input', 'name' => 'searchword', 'placeholder' => Text::_('MOD_SEARCH')],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'task', 'value' => 'search'],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'option', 'value' => 'com_search'],
        ['tag' => 'input', 'type' => 'hidden', 'name' => 'Itemid', 'value' => $params->get('set_itemid', 0) ?: $app->input->getInt('Itemid')],

    ],

]);
