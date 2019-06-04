<?php

if (!$props['content']) {
    return;
}

// Content
echo $this->el('div', [

    'class' => [
        'el-content',
        'uk-text-{content_style}',
        'uk-margin[-{content_margin}]-top {@!content_margin: remove}',
    ],

])->render($element, $props['content']);
