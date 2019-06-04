<?php

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

if (!$params->get('showLast', 1)) {
    array_pop($list);
}

echo HTMLHelper::_('render', 'breadcrumbs', ['items' => $list]);
