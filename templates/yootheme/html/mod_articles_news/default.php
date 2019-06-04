<?php

use Joomla\CMS\Helper\ModuleHelper;

defined('_JEXEC') or die;

foreach ($list as $item) {
	include ModuleHelper::getLayoutPath('mod_articles_news', '_item');
}
