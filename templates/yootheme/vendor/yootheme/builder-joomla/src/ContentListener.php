<?php

namespace YOOtheme\Builder\Joomla;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use YOOtheme\EventSubscriber;

class ContentListener extends EventSubscriber
{
    const PATTERN = '/^<!-- (\{.*\}) -->/';

    protected $user;

    protected $edit;

    protected $isArticle;

    protected $inject = [
        'db' => 'app.db',
        'admin' => 'app.admin',
        'routes' => 'app.routes',
        'builder' => 'app.builder',
        'customizer' => 'theme.customizer',
    ];

    public function onInit($theme)
    {
        $this->routes->post('/page', [$this, 'savePage']);
    }

    public function onSite($theme)
    {
        $input = Factory::getApplication()->input;
        $session = Factory::getSession();

        $this->isArticle = $input->getCmd('option') == 'com_content' && $input->getCmd('view') == 'article' && $input->getCmd('task') == null;

        if ($this->isArticle
            and $this->customizer->isActive()
            and $theme->params->get('admin')
            and $user_id = $theme->params->get('user_id')
        ) {
            $this->user = Factory::getUser();
            $session->set('user', Factory::getUser($user_id));
        }
    }

    public function onDispatch($document, $input)
    {
        $session = Factory::getSession();

        if ($this->user) {
            $session->set('user', $this->user);
        }

        if ($this->admin || !$this->isArticle) {
            return;
        }

        $model = BaseController::getInstance('Content')->getView('article', 'html')->getModel();

        if (!$articleId = $model->getState('article.id') or !$article = $model->getItem($articleId) or !$article->params->get('access-view')) {
            return;
        }

        $edit = '';
        $content = preg_match(self::PATTERN, $article->fulltext, $matches) ? $matches[1] : null;

        if ($article->params->get('access-edit')) {

            if ($this->customizer->isActive()) {

                if ($page = $this->theme->params->get('page')) {
                    if ($article->id === $page['id']) {
                        $content = json_encode($page['content']);
                    } else {
                        unset($page);
                    }
                }

                $modified = !empty($page);

                $data = [
                    'id' => $article->id,
                    'catid' => $article->catid,
                    'title' => $article->title,
                    'content' => $content ? $this->builder->load($content) : $content,
                    'modified' => $modified,
                    'modifiedDate' => $modified ? $page['modifiedDate'] : $this->toDate(@$article->modified),
                    'collision' => $modified ? $this->getCollision($page['modifiedDate'], $article) : false,
                ];

                $this->customizer->addData('page', $data);

            } else {

                $url = Route::_(\ContentHelperRoute::getFormRoute($article->id) . '&return=' . base64_encode(Uri::getInstance()));

                $edit = "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-button uk-button-primary\" href=\"{$url}\">" . \JText::_('JACTION_EDIT') . '</a>';
            }

        }

        $this->theme->set('builder', $content !== null ? compact('content', 'edit') : null);
    }

    public function savePage($page, $overwrite = false, $response)
    {
        if (!$page or !$page = base64_decode($page) or !$page = json_decode($page)) {
            $this->app->abort(500, 'Something went wrong.');
        }

        $data = [
            'id' => $page->id,
            'catid' => $page->catid,
            'title' => $page->title,
            'fulltext' => '<!-- ' . ($text = json_encode($page->content)) . ' -->',
            'introtext' => $this->builder->withParams(['context' => 'content'])->render($text),
        ];

        BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');

        $model = BaseDatabaseModel::getInstance('Article', 'ContentModel', ['ignore_request' => true]);
        $context = 'com_content.article';

        if (!defined('JPATH_COMPONENT')) {
            define('JPATH_COMPONENT', JPATH_BASE . '/components/com_ajax');
        }

        if (!Factory::getUser()->authorise('core.edit', "com_content.article.{$data['id']}")) {
            $this->app->abort(403, 'Insufficient User Rights.');
        }

        if (!$overwrite and $collision = $this->getCollision($page->modifiedDate, $model->getItem($page->id))) {
            return $response->withJSON(compact('collision'));
        }

        if ($tags = (new TagsHelper())->getTagIds($data['id'], $context)) {
            $data['tags'] = explode(',', $tags);
        }

        if (class_exists('FieldsHelper')) {
            foreach (\FieldsHelper::getFields($context, $model->getItem($data['id'])) as $field) {
                $data['com_fields'][$field->name] = $field->value;
            }
        }

        $model->save($data);

        return $response->withJSON([
            'id' => $page->id,
            'modifiedDate' => $this->toDate($model->getItem($data['id'])->modified),
        ]);
    }

    protected function getCollision($modified, $article)
    {
        if ($modified < ($modifiedDate = $this->toDate($article->modified))) {

            $user = Factory::getUser($article->modified_by);
            $modifiedBy = $user ? $user->username : '';

            return compact('modifiedBy', 'modifiedDate');
        }

        return false;
    }

    protected function toDate($date) {
        return date(DATE_W3C, $date ? strtotime($date) : time());
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.site' => 'onSite',
            'dispatch' => ['onDispatch', 10],
        ];
    }
}
