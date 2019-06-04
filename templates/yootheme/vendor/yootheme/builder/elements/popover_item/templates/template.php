<?php

// Display
foreach (['title', 'meta', 'content', 'image', 'link'] as $key) {
    if (!$element["show_{$key}"]) { $props[$key] = ''; }
}

// Image
$props['image'] = $this->render("{$__dir}/template-image", compact('props'));

// Item
$el = $this->el($props['link'] && $element['link_type'] == 'element' ? 'a' : 'div', [
    'class' => [
        'el-item',
        'uk-card uk-card-{card_style}',
        'uk-card-{card_size}',
        'uk-card-hover {@link_type: element}' => $props['link'],
        'uk-card-body' => !($props['image'] && $element['image_card_padding']),
        'uk-margin-remove-first-child' => !($props['image'] && $element['image_card_padding']),
    ],
]);

// Content
$content = $this->el('div', [
    
    'class' => [
        'uk-card-body uk-margin-remove-first-child' => $props['image'] && $element['image_card_padding'],
    ],

]);

// Link
$link = $props['link'] ? $this->el('a', [
    'href' => $props['link'],
    'target' => ['_blank {@link_target}'],
    'uk-scroll' => strpos($props['link'], '#') === 0,
]) : null;

if ($link && $element['link_type'] == 'element') {

    $el->attr($link->attrs + [

        'class' => [
            'uk-display-block uk-link-reset',
        ],

    ]);

    $props['title'] = strip_tags($props['title'], '<br><span>');
    $props['meta'] = strip_tags($props['meta']);
    $props['content'] = strip_tags($props['content']);

} elseif ($link && $element['link_type'] == 'content') {

    if ($props['image']) {
        $props['image'] = $link($element, $props['image']);
    }

    if ($props['title']) {
        $props['title'] = $link($element, ['class' => ['uk-link-reset']], strip_tags($props['title'], '<br><span>'));
    }

} elseif ($link) {

    $link->attr([

        'class' => [
            'el-link',
            'uk-{link_style: link-(muted|text)}',
            'uk-button uk-button-{!link_style: |link-muted|link-text} [uk-button-{link_size}]',
        ],

    ]);

}

// Card media
if ($props['image'] && $element['image_card_padding']) {
    $props['image'] = $this->el('div', ['class' => [
        'uk-card-media-top',
    ]], $props['image'])->render($element);
}

?>

<?= $el($element) ?>

    <?= $props['image'] ?>

    <?php if ($this->expr($content->attrs['class'], $element)) : ?>
        <?= $content($element, $this->render("{$__dir}/template-content", compact('props', 'link'))); ?>
    <?php else : ?>
        <?= $this->render("{$__dir}/template-content", compact('props', 'link')) ?>
    <?php endif ?>

<?= $el->end() ?>
