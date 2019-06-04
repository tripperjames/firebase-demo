<?php
/*
 * The template for displaying categorized articles.
 */

use YOOtheme\Util\Str;

$attrs_container = [];

$get_margin = function ($margin) {

    switch ($margin) {
        case '':
            return;
        case 'default':
            return 'uk-margin-top';
        default:
            return "uk-margin-{$margin}-top";
    }

};

// Image
$attrs_image['class'][] = 'uk-text-center';
$attrs_image['class'][] = $get_margin($params['image_margin']);

// Container
if ((!isset($columns) || $columns == 1) && $params['content_width'] && ($params['content_width'] != $params['width'])) {
    $attrs_container['class'][] = "uk-container uk-container-{$params['content_width']}";
}

// Title
$title_element = !$single ? 'h2' : 'h1';
$attrs_title['class'][] = $get_margin($params['title_margin']) . ' uk-margin-remove-bottom';
$attrs_title['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_title['class'][] = $params['title_style'] ? "uk-{$params['title_style']}" : 'uk-article-title';

// Content
$attrs_content['class'][] = $get_margin($params['content_margin']);
$attrs_content['class'][] = $params['content_align'] ? 'uk-text-center' : '';
$attrs_content['class'][] = $single && $params['content_dropcap'] ? 'uk-dropcap' : '';

// Tags
$attrs_tags['class'][] = $params['header_align'] ? 'uk-text-center' : '';

// Button
$attrs_button['class'][] = "uk-button uk-button-{$params['button_style']}";
$attrs_button_container['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_button_container['class'][] = "uk-margin-{$params['button_margin']}";

/*
 * Image template
 */
$imagetpl = function ($attr) use ($image, $params) {

    if ($this->isImage($image->attrs['src']) == 'svg') {
        $img = $this->image($image->attrs['src'], ['width' => $params['image_width'], 'height' => $params['image_height'], 'uk-img' => true, 'property' => 'url', 'alt' => $image->attrs['alt']]);
    } else {
        $img = $this->image([$image->attrs['src'], 'thumbnail' => [$params['image_width'], $params['image_height']], 'srcset' => true], ['uk-img' => true, 'property' => 'url', 'alt' => $image->attrs['alt']]);
    }

    ?>

    <div<?= $this->attrs($attr) ?> property="image" typeof="ImageObject">
        <?php if ($image->link) : ?>
            <a href="<?= $image->link ?>"><?= $img ?></a>
        <?php else : ?>
            <?= $img ?>
        <?php endif ?>
    </div>

    <?php
};

/*
 * Meta template
 */
$metatpl = function () use ($author, $published, $category, $params, $get_margin) {

    if ($published || $author || $category) {

        $attrs_meta['class'][] = $get_margin($params['meta_margin']) . ' uk-margin-remove-bottom';

        switch ($params['meta_style']) {

            case 'list':

                $parts = array_filter([
                    $published ?: '',
                    $author ? "<span>{$author}</span>" : '',
                    $category ?: '',
                ]);

                $attrs_meta['class'][] = 'uk-subnav uk-subnav-divider';
                $attrs_meta['class'][] = $params['header_align'] ? 'uk-flex-center' : '';

                ?>
                <ul<?= $this->attrs($attrs_meta) ?>>
                    <?php foreach ($parts as $part) : ?>
                    <li><?= $part ?></li>
                    <?php endforeach ?>
                </ul>
                <?php
                break;

            default: // sentence

                $attrs_meta['class'][] = 'uk-article-meta';
                $attrs_meta['class'][] = $params['header_align'] ? 'uk-text-center' : '';

                ?>
                <p<?= $this->attrs($attrs_meta) ?>>
                <?php

                    if ($author && $published) {
                        JText::printf('TPL_YOOTHEME_META_AUTHOR_DATE', $author, $published);
                    } elseif ($author) {
                        JText::printf('TPL_YOOTHEME_META_AUTHOR', $author);
                    } elseif ($published) {
                        JText::printf('TPL_YOOTHEME_META_DATE', $published);
                    }

                ?>
                <?= $category ? JText::sprintf('TPL_YOOTHEME_META_CATEGORY', $category) : '' ?>
                </p>
                <?php
        }

    }

};

?>

<article id="article-<?= $article->id ?>" class="uk-article"<?= $this->attrs(['data-permalink' => $permalink]) ?> typeof="Article">

    <meta property="name" content="<?= $this->e($title) ?>">
    <meta property="author" typeof="Person" content="<?= $this->e($article->author) ?>">
    <meta property="dateModified" content="<?= $this->date($article->modified, 'c') ?>">
    <meta property="datePublished" content="<?= $this->date($article->publish_up, 'c') ?>">
    <meta class="uk-margin-remove-adjacent" property="articleSection" content="<?= $this->e($article->category_title) ?>">

    <?php if ($image && $image->align == 'none') : ?>
    <?php $imagetpl($attrs_image) ?>
    <?php endif ?>

    <?php if ($attrs_container) : ?>
    <div<?= $this->attrs($attrs_container) ?>>
    <?php endif ?>

        <?php if (!$params['info_block_position']) : ?>
        <?php $metatpl() ?>
        <?php endif ?>

        <?php if ($title) : ?>
            <<?= $title_element . $this->attrs($attrs_title) ?>>
                <?= $title ?>
            </<?= $title_element ?>>
        <?php endif ?>

        <?php if ($params['info_block_position']) : ?>
        <?php $metatpl() ?>
        <?php endif ?>

        <?php if ($event) echo $event->afterDisplayTitle ?>

        <?php if ($image && $image->align != 'none') : ?>

            <?php if ($attrs_container) : ?>
            </div>
            <?php endif ?>

            <?php $imagetpl($attrs_image) ?>

            <?php if ($attrs_container) : ?>
            <div<?= $this->attrs($attrs_container) ?>>
            <?php endif ?>

        <?php endif ?>

        <?php if ($event) echo $event->beforeDisplayContent ?>

        <?php if (isset($article->toc) && $article->toc) : ?>
            <?= str_replace(['pull-right', 'nav nav-tabs nav-stacked', 'active"'], ['uk-align-center uk-align-right@m', 'uk-nav uk-nav-default', 'uk-active"'], $article->toc) ?>
        <?php endif ?>

        <?php if ($content) : ?>
        <div <?= $this->attrs($attrs_content) ?> property="text">
            <?php if (is_numeric($params['content_length']) && $params['content_length'] >= 0) : ?>
                <?= Str::limit(strip_tags($content), $params['content_length'], '...', false) ?>
            <?php else : ?>
                <?= $content ?>
            <?php endif ?>
        </div>
        <?php endif ?>

        <?php if ($tags) : ?>
        <p<?= $this->attrs($attrs_tags) ?>><?= $tags ?></p>
        <?php endif ?>

        <?php if ($readmore) : ?>
        <p<?= $this->attrs($attrs_button_container) ?>>
            <a <?= $this->attrs($attrs_button) ?> href="<?= $readmore->link ?>"><?= $readmore->text ?></a>
        </p>
        <?php endif ?>

        <?php if ($created || $modified || $hits) : ?>
        <ul class="uk-list">

            <?php if ($created) : ?>
                <li><?= JText::sprintf('TPL_YOOTHEME_META_DATE_CREATED', $created) ?></li>
            <?php endif ?>

            <?php if ($modified) : ?>
                <li><?= JText::sprintf('TPL_YOOTHEME_META_DATE_MODIFIED', $modified) ?></li>
            <?php endif ?>

            <?php if ($hits) : ?>
                <li><?= JText::sprintf('TPL_YOOTHEME_META_HITS', $hits) ?></li>
            <?php endif ?>

        </ul>
        <?php endif ?>

        <?php if ($icons) : ?>
        <ul class="uk-subnav">
            <?php foreach ($icons as $icon) : ?>
            <li><?= $icon ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <?php if ($pagination) : ?>
        <ul class="uk-pagination uk-margin-medium">

            <?php if ($pagination->prev) : ?>
            <li><a href="<?= $pagination->prev ?>"><span uk-pagination-previous></span> <?= JText::_('JPREVIOUS') ?></a></li>
            <?php endif ?>

            <?php if ($pagination->next) : ?>
            <li class="uk-margin-auto-left"><a href="<?= $pagination->next ?>"><?= JText::_('JNEXT') ?> <span uk-pagination-next></span></a></li>
            <?php endif ?>

        </ul>
        <?php endif ?>

        <?php if ($event) echo $event->afterDisplayContent ?>

    <?php if ($attrs_container) : ?>
    </div>
    <?php endif ?>

</article>
