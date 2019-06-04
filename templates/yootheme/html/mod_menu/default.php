<?php

use Joomla\CMS\HTML\HTMLHelper;
use YOOtheme\Util\Collection;

defined('_JEXEC') or die;

$theme = HTMLHelper::_('theme');
$attrs = ['role' => 'menu', 'class' => []];
$items = [];
$parents = [];

foreach ($list as $_item) {

    $item = clone $_item;
    $alias_id = $item->params->get('aliasoptions');

    // set active state
    if ($item->id == $active_id || ($item->type == 'alias' && $alias_id == $active_id)) {
        $item->active = true;
    }

    if (in_array($item->id, $path)) {
        $item->active = true;
    } elseif ($item->type == 'alias') {
        if (count($path) > 0 && $alias_id == $path[count($path) - 1]) {
            $item->active = true;
        } elseif (in_array($alias_id, $path) && !in_array($alias_id, $item->tree)) {
            $item->active = true;
        }
    }

    // standardise values
    $item->url = $item->flink;
    $item->target = ($item->browserNav == 1 || $item->browserNav == 2) ? '_blank' : '';
    $item->active = $item->active ?: false;
    $item->divider = $item->type === 'separator';
    $item->type = in_array($item->type, ['heading', 'separator']) ? 'header' : $item->type;
    $item->class = trim($params->get('class_sfx').' '.$item->params->get('menu-anchor_css'));

    // set menu config
    $item->config = new Collection($theme->get("menu.items.{$item->id}"));
    $item->config['icon'] = $item->params->get('menu_image');
    $item->config['icon-only'] = !$item->params->get('menu_text');

    // add to parent
    if ($parent = end($parents)) {
        $parent->children[] = $item;
    } else {
        $items[] = $item;
    }

    // set/remove parent
    if ($item->deeper) {
        $parents[] = $item;
        $item->children = [];
    } elseif ($item->shallower) {
        array_splice($parents, -$item->level_diff);
    }
}

$params->set('menu_style', $module->config->get('menu_style'));

echo HTMLHelper::_('render', 'menu/menu', ['items' => $items, 'params' => $params, 'position' => $module->position]);
