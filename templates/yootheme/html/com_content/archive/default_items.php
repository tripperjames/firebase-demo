<?php

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers');

// Parameter shortcuts
$params = $this->params;

foreach ($this->items as $item) {

    // Article
    $article = [
        'article' => $item,
        'params' => $params,
    ];

    // Content
    if ($params->get('show_intro')) {
        $article['content'] = HTMLHelper::_('string.truncate', $item->introtext, $params->get('introtext_limit'));
    }

    echo HTMLHelper::_('render', 'article:archive', $article);
}

echo $this->pagination->getPagesLinks();
