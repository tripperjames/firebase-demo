<?php

// Display
foreach (['title', 'meta', 'content', 'link'] as $key) {
    if (!$element["show_{$key}"]) { $props[$key] = ''; }
}

// Layout
$grid = $this->el('div', [

    'class' => [
        'uk-child-width-{0}[@{breakpoint}]' => $element['width'] == 'expand' ? 'auto' : 'expand',
    ],

    'uk-grid' => true,
]);

$cell = $this->el('div', [

    'class' => [
        'uk-width-{width}[@{breakpoint}]',
        'uk-text-break {@width: small|medium}',
    ],

]);

?>

<?php if ($element['layout'] == 'stacked') : ?>

    <?php if ($element['meta_align'] == 'above-title') : ?>
    <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
    <?php endif ?>

    <?= $this->render("{$__dir}/template-title", compact('props')) ?>

    <?php if (in_array($element['meta_align'], ['below-title', 'above-content'])) : ?>
    <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
    <?php endif ?>

    <?= $this->render("{$__dir}/template-content", compact('props')) ?>

    <?php if ($element['meta_align'] == 'bottom-content') : ?>
    <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
    <?php endif ?>

<?php elseif ($element['layout'] == 'grid-2') : ?>

    <?= $grid($element, ['class' => ['uk-grid-{gutter}']]) ?>
        <?= $cell($element) ?>

            <?php if ($element['meta_align'] == 'above-title') : ?>
            <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
            <?php endif ?>

            <?= $this->render("{$__dir}/template-title", compact('props')) ?>

            <?php if ($element['meta_align'] == 'below-title') : ?>
            <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
            <?php endif ?>

        </div>
        <div>

            <?php if ($element['meta_align'] == 'above-content') : ?>
            <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
            <?php endif ?>

            <?= $this->render("{$__dir}/template-content", compact('props')) ?>

            <?php if ($element['meta_align'] == 'below-content') : ?>
            <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
            <?php endif ?>

        </div>
    </div>

<?php else : ?>

    <?= $grid($element, ['class' => [$element['leader'] && $element['width'] == 'expand' ? 'uk-grid-small uk-flex-bottom' : 'uk-flex-middle [uk-grid-{gutter}]']]) ?>
        <?= $cell($element) ?>
            <?= $this->render("{$__dir}/template-title", compact('props')) ?>
        </div>
        <div>
            <?= $this->render("{$__dir}/template-meta", compact('props')) ?>
        </div>
    </div>

    <?= $this->render("{$__dir}/template-content", compact('props')) ?>

<?php endif ?>
