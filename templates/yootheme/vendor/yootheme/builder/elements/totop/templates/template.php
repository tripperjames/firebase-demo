<?php

$el = $this->el('div');

// Link
$link = $this->el('a', [
    'href' => '#', // WordPress Preview reloads if `href` is empty
    'title' => ['{link_title}'],
    'uk-totop' => true,
    'uk-scroll' => true,
]);

echo $el($props, $attrs, $link($props, ''));
