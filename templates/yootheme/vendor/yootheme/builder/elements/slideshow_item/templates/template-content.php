<?php

// Title
$title = $this->el($element['title_element'], [

    'class' => [
        'el-title',
        'uk-{title_style}',
        'uk-heading-{title_decoration}',
        'uk-text-{title_color} {@!title_color: background}',
        'uk-margin[-{title_margin}]-top {@!title_margin: remove}',
        'uk-margin-remove-top {@title_margin: remove}',
        'uk-margin-remove-bottom',
    ],

    'uk-slideshow-parallax' => $element['parallaxOptions']($element, 'title_'),
]);

// Meta
$meta = $this->el('div', [

    'class' => [
        'el-meta',
        'uk-[text-{@meta_style: meta}]{meta_style}',
        'uk-text-{meta_color}',
        'uk-margin[-{meta_margin}]-top {@!meta_margin: remove}',
        'uk-margin-remove-bottom {@!meta_style: |meta} [uk-margin-{meta_margin: remove}-top]',
    ],

    'uk-slideshow-parallax' => $element['parallaxOptions']($element, 'meta_'),
]);

// Content
$content = $this->el('div', [

    'class' => [
        'el-content',
        'uk-text-{content_style}',
        'uk-margin[-{content_margin}]-top {@!content_margin: remove}',
    ],

    'uk-slideshow-parallax' => $element['parallaxOptions']($element, 'content_'),
]);

// Link
$link = $this->el('a', [

    'class' => [
        'el-link',
        'uk-{link_style: link-(muted|text)}',
        'uk-button uk-button-{!link_style: |link-(muted|text)} [uk-button-{link_size}]',
    ],

    'href' => $props['link'],
    'target' => ['_blank {@link_target}'],
    'uk-scroll' => strpos($props['link'], '#') === 0,
    'uk-slideshow-parallax' => $element['parallaxOptions']($element, 'link_'),
]);

$link_container = $this->el('div', [

    'class' => [
        'uk-margin[-{link_margin}]-top {@!link_margin: remove}',
    ],

]);

?>

<?php if ($props['meta'] && $element['meta_align'] == 'above-title') : ?>
<?= $meta($element, $props['meta']) ?>
<?php endif ?>

<?php if ($props['title']) : ?>
<?= $title($element) ?>
    <?php if ($element['title_color'] == 'background') : ?>
    <span class="uk-text-background"><?= $props['title'] ?></span>
    <?php elseif ($element['title_decoration'] == 'line') : ?>
    <span><?= $props['title'] ?></span>
    <?php else : ?>
    <?= $props['title'] ?>
    <?php endif ?>
<?= $title->end() ?>
<?php endif ?>

<?php if ($props['meta'] && $element['meta_align'] == 'below-title') : ?>
<?= $meta($element, $props['meta'] ?: '') ?>
<?php endif ?>

<?php if ($props['content']) : ?>
<?= $content($element, $props['content']) ?>
<?php endif ?>

<?php if ($props['meta'] && $element['meta_align'] == 'below-content') : ?>
<?= $meta($element, $props['meta']) ?>
<?php endif ?>

<?php if ($props['link'] && ($props['link_text'] || $element['link_text'])) : ?>
<?= $link_container($element, $link($element, $props['link_text'] ?: $element['link_text'])) ?>
<?php endif ?>
