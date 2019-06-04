<?php

$el = $this->el('div', $attrs);

$el->attr([

    'class' => [
        'uk-grid-{gutter}',
        'uk-grid-divider {@divider} {@!gutter: collapse}',
    ],

    'uk-grid' => true,

    // Height Viewport
    'uk-height-viewport' => [
        'offset-top: true; {@height}',
        'offset-bottom: 20; {@height: percent}',
    ],

    // Match height if single panel element inside cell
    'uk-height-match' => ['target: .uk-card; row: false {@match}'],
]);

// Margin
$margin = $this->el('div', [
    'class' => [

        'uk-grid-margin[-{gutter}] {@!margin} {@gutter: |small|medium|large}',

        'uk-margin {@margin: default}',
        'uk-margin-{!margin: |default}',
        'uk-margin-remove-top {@margin_remove_top}{@!margin: remove-vertical}',
        'uk-margin-remove-bottom {@margin_remove_bottom}{@!margin: remove-vertical}',

        'uk-container {@width}',
        'uk-container-{width}{@width: xsmall|small|large|expand}',
        'uk-container-expand-{width_expand} {@width: default|xsmall|small|large}',
    ],
]);

echo $props['width']
    ? $margin($props, $el($props, $builder->render($children)))
    : $el($props, $margin->attrs, $builder->render($children));
