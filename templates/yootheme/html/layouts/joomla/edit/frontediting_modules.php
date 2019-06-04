<?php

defined('JPATH_BASE') or die;

$mod = $displayData['module'];

if (!intval($mod->id)) {
    return;
}

include JPATH_ROOT.'/layouts/joomla/edit/frontediting_modules.php';
