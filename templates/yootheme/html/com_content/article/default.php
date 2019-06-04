<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers');

// Parameter shortcuts
$user = Factory::getUser();
$item = $this->item;
$params = $item->params;
$uncategorised = $item->category_alias == 'uncategorised';

// Pass layout to main template
if (!$uncategorised) {
    $app = Factory::getApplication();
    $app->input->set('layout', 'post');
}

// Heading
if ($this->params->get('show_page_heading')) {
    echo '<h1>'.$this->escape($this->params->get('page_heading')).'</h1>';
}

// Article
$article = [
    'article' => $item,
    'params' => $params,
    'content' => '',
    'single' => true,
];

// Title
$params->set('link_titles', false);

// Content
if ($params->get('access-view')) {

    if ($params->get('urls_position') === '0') {
        $article['content'] .= $this->loadTemplate('links');
    }

    $article['content'] .= $item->text;

    if ($params->get('urls_position') === '1') {
        $article['content'] .= $this->loadTemplate('links');
    }

    $article['image'] = 'fulltext';

// Optional teaser intro text for guests
} elseif ($params->get('show_noauth') && $user->get('guest')) {

    $article['content'] .= $item->introtext;

    // Optional link to let them register to see the whole article
    $item->readmore = $params->get('show_readmore') && !$item->fulltext;
}

// Icons
if ($this->print) {
    $article['icons'] = ['print' => HTMLHelper::_('icon.print_screen', $item, $params)];
}

echo HTMLHelper::_('render', $uncategorised ? 'article:page' : 'article:blog', $article);
