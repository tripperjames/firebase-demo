<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

// Register helpers
HTMLHelper::register('attrs', [$this->view, 'attrs']);
HTMLHelper::register('render', [$this->view, 'render']);
HTMLHelper::register('section', [$this->view, 'section']);

// Add article loader
$this->view->addLoader(function ($name, $parameters, $next) use ($theme) {

    $defaults = array_fill_keys(['title', 'author', 'content', 'hits', 'created', 'modified', 'published', 'category', 'image', 'tags', 'icons', 'readmore', 'pagination', 'link', 'permalink', 'event', 'single'], null);

    // Vars
    extract(array_replace($defaults, $parameters), EXTR_SKIP);

    // Params
    if (!isset($params)) {
        $params = $article->params;
    } elseif (is_array($params)) {
        $params = new Registry($params);
    }

    // Link
    if (!isset($link)) {
        $link = ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language);
    }

    // Permalink
    if (!isset($permalink)) {
        $permalink = Route::_($link, true, -1);
    }

    if ($params['access-view'] === false) {
        $menu = Factory::getApplication()->getMenu()->getActive();
        $link = new Uri(Route::_("index.php?option=com_users&view=login&Itemid={$menu->id}", false));
        $link->setVar('return', base64_encode(Route::_($link, false)));
    }

    // Title
    if ($params['show_title']) {

        $title = $article->title;

        if ($params['link_titles']) {
            $title = HTMLHelper::_('link', $link, $title, ['class' => 'uk-link-reset']);
        }
    }

    // Author
    if ($params['show_author']) {

        $author = $article->created_by_alias ?: $article->author;

        if ($params['link_author'] && $article->contact_link) {
            $author = HTMLHelper::_('link', $article->contact_link, $author);
        }
    }

    if (!empty($article->created_by_alias)) {
        $article->author = $article->created_by_alias;
    }

    // Hits
    if ($params['show_hits']) {
        $hits = $article->hits;
    }

    // Create date
    if ($params['show_create_date']) {
        $created = HTMLHelper::_('date', $article->created, Text::_('DATE_FORMAT_LC3'));
        $created = '<time datetime="'.HTMLHelper::_('date', $article->created, 'c')."\">{$created}</time>";
    }

    // Modify date
    if ($params['show_modify_date']) {
        $modified = HTMLHelper::_('date', $article->modified, Text::_('DATE_FORMAT_LC3'));
        $modified = '<time datetime="'.HTMLHelper::_('date', $article->modified, 'c')."\">{$modified}</time>";
    }

    // Publish date
    if ($params['show_publish_date']) {
        $published = HTMLHelper::_('date', $article->publish_up, Text::_('DATE_FORMAT_LC3'));
        $published = '<time datetime="'.HTMLHelper::_('date', $article->publish_up, 'c')."\">{$published}</time>";
    }

    // Category
    if ($params['show_category']) {

        $category = $article->category_title;

        if ($params['link_category'] && $article->catslug) {
           $category = HTMLHelper::_('link', Route::_(ContentHelperRoute::getCategoryRoute($article->catslug)), $category);
        }
    }

    // Image
    if (is_string($image)) {

        $images = new Registry($article->images);
        $imageType = $image;

        if ($images->get("image_{$imageType}")) {

            $image = new stdClass();
            $image->link = $params['link_titles'] ? $link : null;
            $image->align = $images->get("float_{$imageType}") ?: $params["float_{$imageType}"];
            $image->caption = $images->get("image_{$imageType}_caption");
            $image->attrs = [
                'src' => $images->get("image_{$imageType}"),
                'alt' => $images->get("image_{$imageType}_alt"),
                'title' => $image->caption,
            ];

        } else {

            $image = null;
        }
    }

    // Tags
    if ($params->get('show_tags', 1) && !empty($article->tags->itemTags)) {

        $layout = new JLayoutFile('joomla.content.tags');

        // check for override in child theme
        if ($child = $this->theme->get('child_theme')) {
            $layout->addIncludePath(JPATH_BASE."/templates/{$this->theme->template}_{$child}/html/layouts");
        }

        $tags = $layout->render($article->tags->itemTags);
    }

    // Icons
    if (!isset($icons)) {
        $icons['print'] = $params['show_print_icon'] ? HTMLHelper::_('icon.print_popup', $article, $params) : '';
        $icons['email'] = $params['show_email_icon'] ? HTMLHelper::_('icon.email', $article, $params) : '';
        $icons['edit'] = $params['access-edit'] ? HTMLHelper::_('icon.edit', $article, $params) : '';
    }

    $icons = array_filter($icons);

    // Readmore
    if (!$single && $params['show_readmore'] && (!empty($article->readmore) || (is_numeric($theme->get('blog.content_length')) && (int) $theme->get('blog.content_length') >= 0))) {

        $readmore = new stdClass();

        if ($params['access-view']) {

            $readmore->link = $link;
            $attribs = new Registry($article->attribs);

            if (!$readmore->text = $attribs->get('alternative_readmore')) {
                $readmore->text = Text::_($params['show_readmore_title'] ? 'COM_CONTENT_READ_MORE' : 'TPL_YOOTHEME_READ_MORE');
            }

        } else {

            $readmore->link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid='.$menu->id, false));
            $readmore->link->setVar('return', base64_encode(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language)));

            $readmore->text = Text::_('COM_CONTENT_REGISTER_TO_READ_MORE');
        }

        if ($params['show_readmore_title']) {
            $readmore->text .= HTMLHelper::_('string.truncate', $article->title, $params['readmore_limit']);
        }
    }

    // Pagination
    if (isset($article->pagination)) {
        $pagination = new stdClass();
        $pagination->prev = $article->prev;
        $pagination->next = $article->next;
    }

    // Event
    if (isset($article->event)) {

        $event = $article->event;

        if (!$single && $params['show_intro']) {
            $event->afterDisplayTitle = '';
        }
    }

    // Blog
    if (in_array($name, ['article:blog', 'article:featured', 'article:tag'])) {

        $data = $theme->get('post', []);

        // Merge blog config?
        if (!$single) {
            $data->merge($theme->get('blog', []));
        }

        // Has excerpt field?
        if (!$single && isset($article->jcfields)) {
            foreach ($article->jcfields as $field) {
                if ($field->name === 'excerpt' && $field->rawvalue) {
                    $content = $field->rawvalue;
                    break;
                }
            }
        }

        $params->loadArray($data->all());
    }

    return $next($name, array_diff_key(get_defined_vars(), array_flip(['data', 'next', 'name', 'parameters', 'defaults'])));

}, 'article*');
