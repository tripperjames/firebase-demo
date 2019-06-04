<?php

$props['overlay_cover'] = $props['overlay_style'] && $props['overlay_mode'] == 'cover';

$el = $this->el('div', [

    'uk-filter' => [
        '.js-filter' => $tags,
    ],

]);

// Grid
$grid = $this->el('div', [

    'class' => [
        'js-filter' => $tags,
        'uk-child-width-1-{grid_default}',
        'uk-child-width-1-{grid_small}@s',
        'uk-child-width-1-{grid_medium}@m',
        'uk-child-width-1-{grid_large}@l',
        'uk-child-width-1-{grid_xlarge}@xl',
        'uk-grid-{gutter}',
        'uk-grid-divider {@divider}',
    ],

    'uk-grid' => $this->expr([
        'masonry: {grid_masonry};',
        'parallax: {grid_parallax};',
    ], $props) ?: true,

    'uk-lightbox' => [
        'toggle: a[data-type];' => $props['lightbox'],
    ],

]);

// Orientation
$cell = $this->el('div', [

    'class' => [
        'uk-flex uk-flex-center uk-flex-middle {@image_orientation}',
    ],

]);

// Filter
$filter_grid = $this->el('div', [

    'class' => [
        'uk-child-width-expand',
        'uk-grid-{filter_gutter}',
    ],

    'uk-grid' => true,
]);

$filter_cell = $this->el('div', [

    'class' => [
        'uk-width-{filter_grid_width}@{filter_breakpoint}',
        'uk-flex-last@{filter_breakpoint} {@filter_position: right}',
    ],

]);

?>

<?php if ($tags) : ?>
<?= $el($props, $attrs) ?>

    <?php if ($filter_horizontal = in_array($props['filter_position'], ['left', 'right'])) : ?>
    <?= $filter_grid($props) ?>
        <?= $filter_cell($props) ?>
    <?php endif ?>

        <?= $this->render("{$__dir}/template-nav", compact('props')) ?>

    <?php if ($filter_horizontal) : ?>
        </div>
        <div>
    <?php endif ?>

        <?= $grid($props) ?>
        <?php foreach ($children as $child) : ?>
        <?= $cell($props, ['data-tag' => $child->tags], $builder->render($child, ['element' => $props])) ?>
        <?php endforeach ?>
        </div>

    <?php if ($filter_horizontal) : ?>
        </div>
    </div>
    <?php endif ?>

</div>
<?php else : ?>
<?= $el($props, $attrs) ?>

    <?= $grid($props) ?>
    <?php foreach ($children as $child) : ?>
    <?= $cell($props, $builder->render($child, ['element' => $props])) ?>
    <?php endforeach ?>
    </div>

</div>
<?php endif ?>
