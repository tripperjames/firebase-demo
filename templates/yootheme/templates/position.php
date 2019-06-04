<?php

// Blank
if (empty($style)) {

    if ($center = $name === 'navbar' && in_array($theme->get('header.layout'), ['offcanvas-center-a', 'modal-center-a'])) {
        echo '<div class="uk-margin-auto-vertical">';
    }

    foreach ($items as $index => $item) {
        echo $this->render('module', ['index' => $index, 'module' => $item, 'position' => $name]);
    }

    if ($center) {
        echo '</div>';
    }

    return;
}

// Cell
if ($style == 'cell') {

    foreach ($items as $index => $item) {
        echo "<div>{$this->render('module', ['index' => $index, 'module' => $item, 'position' => $name])}</div>";
    }

    return;
}

// Section
if ($style == 'section') {

    $section = [];

    foreach ($items as $index => $item) {

        if (preg_match('/<!-- (\{.*\}) -->/si', $item->content, $matches)) {

            if ($section) {
                echo $this->render('section', ['name' => $name, 'items' => $section]); $section = [];
            }

            $content = preg_replace('/<div class="uk-text-danger(.*?)<\/div>/si', '', $item->content);

            echo str_replace($matches[0], $theme->builder->render($matches[1], ['prefix' => is_numeric($item->id) ? "module-{$item->id}" : $item->id]), $content);

        } else {
            $section[] = $item;
        }
    }

    if ($section) {
        echo $this->render('section', ['name' => $name, 'items' => $section]);
    }

    return;
}

// Grid
$grid = isset($position) ? $position : $theme->get($name, [])->all();
$visibilities = ['xs', 's', 'm', 'l', 'xl'];
$visible = 4;

// Widgets/Modules
$widgets = [];
foreach ($items as $index => $item) {
    $visibility = $item->config['visibility'];
    $visible = min(array_search($visibility, $visibilities), $visible);

    $widgets[] = $this->el(
        'div',
        [
            'class' => [
                'uk-width-{0}@{breakpoint}' => $item->config['width'],
                'uk-visible@{0}' => $visibility,
            ],
        ],
        $this->render('module', ['index' => $index, 'module' => $item, 'position' => $name])
    );
}

$el = $this->el('div', [
    'class' => [
        'uk-child-width-1-1' => $style == 'grid-stack',
        'uk-child-width-expand@{breakpoint}' => $style != 'grid-stack',
        'uk-grid-{grid_gutter}',
        'uk-grid-divider {@grid_divider}',
        'uk-flex-{vertical_align}',
        'uk-visible@{0}' => $visible ? $visibilities[$visible] : false,
    ],
    'uk-grid' => true,
    'uk-height-match' => ['target: .uk-card; row: false {@match}'],
]);

?>

<?= $el($grid) ?>
<?php foreach ($widgets as $widget) : ?>
    <?= $widget($grid) ?>
<?php endforeach ?>
<?= $el->end() ?>
