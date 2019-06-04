<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Uri\Uri;
use YOOtheme\Util\Str;

class ArticleHelper
{
    public function get($params)
    {
        // Ordering
        $direction = null;

        switch ($params['order']) {
            case 'random':
                $ordering = 'RAND()';
                break;
            case 'date':
                $ordering = 'created';
                break;
            case 'rdate':
                $ordering = 'created';
                $direction = 'DESC';
                break;
            case 'modified':
                $ordering = 'modified';
                break;
            case 'rmodified':
                $ordering = 'modified';
                $direction = 'DESC';
                break;
            case 'alpha':
                $ordering = 'title';
                break;
            case 'ralpha':
                $ordering = 'title';
                $direction = 'DESC';
                break;
            case 'hits':
                $ordering = 'hits';
                break;
            case 'rhits':
                $ordering = 'hits';
                $direction = 'DESC';
                break;
            case 'ordering':
            default:
                $ordering = 'a.ordering';
                break;
        }

        jimport('legacy.model.legacy');

        BaseDatabaseModel::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

        $model = BaseDatabaseModel::getInstance('Articles', 'ContentModel', ['ignore_request' => true]);
        $model->setState('params', ComponentHelper::getParams('com_content'));
        $model->setState('filter.published', 1);
        $model->setState('filter.access', true);
        $model->setState('list.ordering', $ordering);
        $model->setState('list.direction', $direction);
        $model->setState('list.start', 0);
        $model->setState('list.limit', (int) $params['items']);

        // categories filter
        if (($categories = (array) $params['catid']) && count($categories)) {
            $model->setState('filter.category_id', $categories);
        }

        $model->setState('filter.subcategories', $params['subcategories']);
        $model->setState('filter.max_category_levels', 999);

        // featured, accepted values ('hide' || 'only')
        if (!empty($params['featured'])) {
            $model->setState('filter.featured', $params['featured']);
        }

        return $model->getItems();
    }

    public function getUrl($item)
    {
        if (!class_exists('ContentHelperRoute')) {
            require_once(JPATH_SITE . '/components/com_content/helpers/route.php');
        }

        return Route::_(\ContentHelperRoute::getArticleRoute($item->id, $item->catid));
    }

    public static function getRoute($article, $mode = 1)
    {
        if (!class_exists('ContentHelperRoute')) {
            \JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
        }

        $route = \ContentHelperRoute::getArticleRoute($article->id, $article->catid, $article->language);
        $router = clone Router::getInstance('site');
        $router->setMode($mode);

        $site = (string) $router->build($route);

        // Workaround for bug in Joomla 3.7
        $base = Uri::root(true) . '/administrator';
        if (Str::startsWith($site, $base)) {
            $site = Uri::root(true) . Str::substr($site, strlen($base));
        }

        return $site;
    }
}
