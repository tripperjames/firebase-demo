<?php

$el = $this->el('div');

// Grid
$grid = $this->el('div', [

    'class' => [
        'uk-flex-middle [uk-grid-{gutter}]',
        'uk-child-width-{0}' => $props['fullwidth'] ? '1-1' : 'auto',
        'uk-flex-{text_align}[@{text_align_breakpoint} [uk-flex-{text_align_fallback}]] {@!fullwidth}',
    ],

    'uk-grid' => true,
]);

?>

<?= $el($props, $attrs) ?>

    <?php if (count($children) > 1) : ?>
    <?= $grid($props) ?>
    <?php endif ?>

    <?php foreach ($children as $child) : ?>

        <?php if (count($children) > 1) : ?>
        <div class="el-item">
        <?php endif ?>

        <?= $builder->render($child, ['element' => $props]) ?>

        <?php if (count($children) > 1) : ?>
        </div>
        <?php endif ?>

    <?php endforeach ?>

    <?php if (count($children) > 1) : ?>
    </div>
    <?php endif ?>

</div>
