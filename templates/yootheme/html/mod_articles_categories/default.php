<?php

use Joomla\CMS\Helper\ModuleHelper;

defined('_JEXEC') or die;

?>
<ul class="categories-module">
    <?php require ModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default').'_items'); ?>
</ul>
