<?php

// Resets
if (!$props['image']) {
    $props['media_overlay'] = false;
    $props['media_overlay_gradient'] = false;
}

$el = $this->el('div', [

    'class' => [

        // Vertical alignment
        // Can't use `uk-flex` and `uk-width-1-1` instead of `uk-grid-item-match` because it doesn't work with grid divider (it removes the ::before)
        'uk-grid-item-match uk-flex-{vertical_align} {@!style} {@!image}',

        // Text color
        'uk-{text_color} {@!style: primary|secondary}' => !$props['style'] || $props['image'],

        'uk-grid-item-match'  => $props['style'] || $props['image'],
    ],

]);

// Column options

// Overlay
$overlay = $props['media_overlay'] || $props['media_overlay_gradient']
    ? $this->el('div', [
    'class' => ['uk-position-cover'],
    'style' => [
        'background-color: {media_overlay};',
        // `background-clip` fixes sub-pixel issue
        'background-image: {media_overlay_gradient}; background-clip: padding-box',
    ],
    ]) : null;

// Tile
$tile = $props['style'] || $props['image']
    ? $this->el('div', [
    'class' => [
        'uk-tile',

        // Padding
        'uk-padding-remove {@padding: none}',
        'uk-tile-{!padding: |none}',

        // Vertical alignment
        // Can't use `uk-flex` and `uk-width-1-1` instead of `uk-grid-item-match` because it doesn't work with grid divider (it removes the ::before)
        'uk-grid-item-match uk-flex-{vertical_align}',
    ],
    ]) : null;

$tile_container = $this->el('div', [
    'class' => [
        'uk-tile-{style}',

        'uk-grid-item-match {@image}',

        // Overlay
        'uk-position-relative' => $overlay,

        // Text color
        'uk-preserve-color {@preserve_color} {@style: primary|secondary}',
    ],
]);

// Image
if ($props['image']) {

    $tile->attr($this->bgImage($props['image'], [
        'width' => $props['image_width'],
        'height' => $props['image_height'],
        'size' => $props['image_size'],
        'position' => $props['image_position'],
        'visibility' => $props['image_visibility'],
        'blend_mode' => $props['media_blend_mode'],
        'background' => $props['media_background'],
        'effect' => $props['image_effect'],
        'parallax_bgx_start' => $props['image_parallax_bgx_start'],
        'parallax_bgy_start' => $props['image_parallax_bgy_start'],
        'parallax_bgx_end' => $props['image_parallax_bgx_end'],
        'parallax_bgy_end' => $props['image_parallax_bgy_end'],
        'parallax_breakpoint' => $props['image_parallax_breakpoint'],
    ]));

}

// Fix margin if container
$container = $props['vertical_align'] || $overlay
    ? $this->el('div', [
    'class' => [
        'uk-panel',

        // Make sure overlay is always below content
        'uk-position-relative' => $overlay,
    ],
    ]) : null;

?>

<?= $el($props, $attrs) ?>

    <?php if ($tile) : ?>
    <?= $tile_container($props, !$props['image'] ? $tile->attrs : []) ?>
    <?php endif ?>

        <?php if ($props['image']) : ?>
        <?= $tile($props) ?>
        <?php endif ?>

            <?php if ($overlay) : ?>
            <?= $overlay($props, '') ?>
            <?php endif ?>

            <?php if ($container) : ?>
            <?= $container($props) ?>
            <?php endif ?>

                <?= $builder->render($children) ?>

            <?php if ($container) : ?>
            </div>
            <?php endif ?>

        <?php if ($props['image']) : ?>
        </div>
        <?php endif ?>

    <?php if ($tile) : ?>
    </div>
    <?php endif ?>

</div>
