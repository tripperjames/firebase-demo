<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers');

// Theme
$theme = HTMLHelper::_('theme');

// Parameter shortcuts
$params = $this->params;
$lead = $this->lead_items ?: [];
$intro = $this->intro_items ?: [];
$columns = max(1, $params->get('num_columns'));

// Article template
$article = HTMLHelper::_('render', 'article:blog', function ($item) use ($columns) {
    return [
        'article' => $item,
        'content' => $item->introtext,
        'image' => 'intro',
        'columns' => $columns,
    ];
});

?>

<?php if ($params->get('show_page_heading')
        || $params->get('page_subheading')
        || $params->get('show_category_title', 1)
        || ($params->def('show_description_image', 1) && $this->category->getParams()->get('image'))
        || ($params->get('show_description', 1) && $this->category->description)
        || ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags))
    ) : ?>

<div class="uk-panel uk-margin-medium-bottom">

    <?php if ($params->get('show_page_heading')) : ?>
    <h1><?= $this->escape($params->get('page_heading')) ?></h1>
    <?php endif ?>

    <?php if ($params->get('page_subheading')) : ?>
    <h2><?= $this->escape($params->get('page_subheading')) ?></h2>
    <?php endif ?>

    <?php if ($params->get('show_category_title')) : ?>
    <h3><?= $this->category->title ?></h3>
    <?php endif ?>

    <?php if ($params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
    <img src="<?= $this->category->getParams()->get('image') ?>" alt="<?= htmlspecialchars($this->category->getParams()->get('image_alt'))?>">
    <?php endif ?>

    <?php if ($params->get('show_description') && $this->category->description) : ?>
    <div class="uk-margin"><?= HTMLHelper::_('content.prepare', $this->category->description, '', 'com_content.category') ?></div>
    <?php endif ?>

    <?php if ($params->get('show_cat_tags') && !empty($this->category->tags->itemTags)) : ?>
        <?= LayoutHelper::render('joomla.content.tags', $this->category->tags->itemTags) ?>
    <?php endif ?>

</div>
<?php endif ?>

<?php if (empty($this->lead_items) && empty($this->intro_items) && empty($this->link_items)) : ?>
    <?php if ($params->get('show_no_articles', 1)) : ?>
    <p><?= Text::_('COM_CONTENT_NO_ARTICLES') ?></p>
    <?php endif ?>
<?php endif ?>

<?php if ($lead) : ?>
    <?php foreach ($lead as $item) : ?>
    <?= $article($item) ?>
    <?php endforeach ?>
<?php endif ?>

<?php

if ($intro) :

    // Grid
    if ($columns != 1) {

        $attrs = [];
        $options = [];

        $options[] = $theme->get('blog.grid_masonry') ? 'masonry: true' : '';
        $options[] = $theme->get('blog.grid_parallax') ? "parallax: {$theme->get('blog.grid_parallax')}" : '';
        $attrs['uk-grid'] = implode(';', array_filter($options)) ?: true;

        // Columns
        $breakpoints = ['s', 'm', 'l', 'xl'];
        $breakpoint = $theme->get('blog.column_breakpoint');
        $pos = array_search($breakpoint, $breakpoints);

        if ($pos === false) {
            $attrs['class'][] = "uk-child-width-1-{$columns}";
        } else {
            for ($i = $columns; $i > 0; $i--) {
                if (($pos > -1) && ($i > 1)) {
                    $attrs['class'][] = "uk-child-width-1-{$i}@{$breakpoints[$pos]}";
                    $pos--;
                }
            }
        }

        $attrs['class'][] = $theme->get('blog.column_gutter') ? 'uk-grid-large' : '';
        $attrs['class'][] = $lead ? 'uk-margin-large-top' : '';

    }

?>

    <?php if ($columns != 1) : ?>
    <div <?= $theme->view->attrs($attrs) ?>>
    <?php endif ?>

        <?php foreach ($intro as $i => $item) : ?>
            <?php if ($columns != 1) : ?>
                <div><?= $article($item) ?></div>
            <?php else : ?>
                <?= $article($item) ?>
            <?php endif ?>
        <?php endforeach ?>

    <?php if ($columns != 1) : ?>
    </div>
    <?php endif ?>

<?php endif ?>

<?php if (!empty($this->link_items)) : ?>
<div class="uk-margin-large<?= $theme->get('post.header_align') ? ' uk-text-center' : '' ?>">

    <h3><?= Text::_('COM_CONTENT_MORE_ARTICLES') ?></h3>

    <ul class="uk-list">
        <?php foreach ($this->link_items as $item) : ?>
        <li><a href="<?= Route::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)) ?>"><?= $item->title ?></a></li>
        <?php endforeach ?>
    </ul>

</div>
<?php endif ?>

<?php if (($params->def('show_pagination', 1) == 1 || ($params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>

    <?php if ($theme->get('blog.navigation') == 'pagination') : ?>
        <?= $this->pagination->getPagesLinks() ?>
    <?php endif ?>

    <?php if ($theme->get('blog.navigation') == 'previous/next') : ?>
    <ul class="uk-pagination uk-margin-large">

        <?php if ($prevlink = $this->pagination->getData()->previous->link) : ?>
        <li><a href="<?= $prevlink ?>"><span uk-pagination-previous></span> <?= Text::_('JPREV') ?></a></li>
        <?php endif ?>

        <?php if ($nextlink = $this->pagination->getData()->next->link) : ?>
        <li class="uk-margin-auto-left"><a href="<?= $nextlink ?>"><?= Text::_('JNEXT') ?> <span uk-pagination-next></span></a></li>
        <?php endif ?>

    </ul>
    <?php endif ?>

<?php endif ?>