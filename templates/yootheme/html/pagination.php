<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use YOOtheme\Util\File;

defined('_JEXEC') or die;

if (in_array(basename(JPATH_COMPONENT), ['com_content', 'com_finder', 'com_search', 'com_tags'])) {

    // prefer child theme's pagination
    if (File::normalizePath(__FILE__) !== $file = File::find('@theme/html/pagination.php')) {
        return include $file;
    }

    function pagination_list_render($list) {

        // find out the id of the page, that is the current page
        $currentId = 0;

        foreach ($list['pages'] as $id => $page) {
            if (!$page['active']) {
                $currentId = $id;
            }
        }

        // set the range for the inner pages that should be displayed
        // this displays + - $range page-buttons arround the current page
        // due to joomla-restrictions there won't be displayed more than -5 and +4 buttons.
        $range = 3;

        // start building pagination-list
        $html = ['<ul class="uk-pagination uk-margin-large uk-flex-center">'];

        // add first-button
        if ($list['start']['active'] == 1) {
            $html[] = $list['start']['data'];
        }

        // add previous-button
        if ($list['previous']['active'] == 1) {
            $html[] = $list['previous']['data'];
        }

        // add buttons for sourrounding pages
        foreach ($list['pages'] as $id => $page) {
            // only show the buttons that are within the range
            if ($id <= $currentId + $range && $id >= $currentId - $range) {
                $html[] = $page['data'];
            }
        }

        // add next-button
        if ($list['next']['active'] == 1) {
            $html[] = $list['next']['data'];
        }

        // add last-button
        if ($list['end']['active'] == 1) {
            $html[] = $list['end']['data'];
        }

        // close pagination-list
        $html[] = '</ul>';

        return implode("\n", $html);
    }

    function pagination_item_active($item) {

        $theme = HTMLHelper::_('theme');

        if (in_array($item->text, [Text::_('JLIB_HTML_START'), Text::_('JLIB_HTML_END')]) && !$theme->get('blog.pagination_startend')) {
            return '';
        }

        $cls = '';
        $title = '';

        if ($item->text == Text::_('JNEXT')) {
            $item->text = '<span uk-pagination-next></span>';
            $cls = 'next';
            $title = Text::_('JNEXT');
        }
        elseif ($item->text == Text::_('JPREV')) {
            $item->text = '<span uk-pagination-previous></span>';
            $cls = 'previous';
            $title = Text::_('JPREV');
        }
        elseif ($item->text == Text::_('JLIB_HTML_START')) {
            $cls = 'first';
            $title = Text::_('JLIB_HTML_START');
        }
        elseif ($item->text == Text::_('JLIB_HTML_END')) {
            $cls = 'last';
            $title = Text::_('JLIB_HTML_END');
        }

        return '<li><a class="'.$cls.'" href="'.$item->link.'" title="'.$title.'">'.$item->text.'</a></li>';
    }

    function pagination_item_inactive(&$item) {
        return '<li class="uk-active"><span>'.$item->text.'</span></li>';
    }

}
