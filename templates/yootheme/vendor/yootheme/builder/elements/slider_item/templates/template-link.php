<?php

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

    if ($props['title']) {
        $props['title'] = $link($element, ['class' => ['uk-link-reset']], strip_tags($props['title'], '<br><span>'));
    }

} elseif ($link) {

    $link->attr([

        'class' => [
            'el-link',
            'uk-{link_style: link-(muted|text)}',
            'uk-button uk-button-{!link_style: |link-muted|link-text} [uk-button-{link_size}]',
            'uk-transition-{link_transition} {@overlay_hover}',
        ],

    ]);

}

return $link;
