<?php

if (!$props['title']) {
    return;
}

// Title
$title = $this->el('span', [

    'class' => [
        'el-title',
        'uk-display-block {@title_style: |strong}',
        'uk-text-{title_color} {@!title_color: background}',
    ],

]);

// Leader
if ($element['leader'] && $element['layout'] == 'grid-2-m' && $element['width'] == 'expand') {
    $title->attr('uk-leader', $element['breakpoint'] ? ['media: @{breakpoint}'] : true);
}

// Color
if ($element['title_color'] == 'background') {
    $props['title'] = "<span class=\"uk-text-background\">{$props['title']}</span>";
}

// Colon
if ($element['title_colon']) {
    $props['title'] .= ':';
}

?>

<?php if ($element['title_style'] == 'strong') : ?>
    <?= $title($element, [], $props['title'], 'strong') ?>
<?php elseif (preg_match('/^h[1-6]$/', $element['title_style'])) : ?>
    <?= $title($element, ['class' => ['uk-{title_style} uk-margin-remove']], $props['title'], 'h3') ?>
<?php else : ?>
    <?= $title($element, $props['title']) ?>
<?php endif ?>
