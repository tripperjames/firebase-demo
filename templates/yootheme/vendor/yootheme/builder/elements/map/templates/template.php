<?php

$el = $this->el('div', [

    'class' => [
        'uk-position-relative',
        'uk-position-z-index',
    ],

    'style' => [
        'width: {width}px {@!width_breakpoint}',
        'height: {height}px {@!width}',
    ],

    'uk-responsive' => [
        'width: {width}; height: {height}'
    ],

    'uk-map' => json_encode([
        'map' => $options,
    ]),

]);

// Width and Height
$props['width'] = trim($props['width'], 'px');
$props['height'] = trim($props['height'] ?: '300', 'px');

echo $el($props, $attrs, '');
