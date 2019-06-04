<?php

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

foreach ($this->results as $result) {

    $article = [

        // Article
        'article' => $result,
        'content' => $result->text,
        'link' => $result->href,

        // Params
        'params' => [
            'show_title' => true,
            'link_titles' => true,
        ],

    ];

    echo HTMLHelper::_('render', 'article:search', $article);
}

echo $this->pagination->getPagesLinks();
