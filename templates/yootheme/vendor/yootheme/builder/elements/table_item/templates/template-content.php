<?php

if (!$props['content']) {
    return;
}

// Content
$el = $this->el('div', [

    'class' => [
        'el-content',
        'uk-text-{content_style}',
    ],

]);

echo $el($element, $props['content']);
