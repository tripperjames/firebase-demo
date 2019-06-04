<?php

$class = [];
$badge = [];
$title = [];

$layout = $theme->get('header.layout');
$toggle = $position == 'navbar' && (strpos($layout, 'offcanvas') === 0 || $module->type == 'menu') || strpos($layout, 'modal') === 0;
$alignment = false;

if ($toggle) {
    $alignment = $index == 1 && strpos($layout, '-top-b') ? 'top' : '';
    $alignment = $index == 1 && strpos($layout, '-left-b') ? 'left' : $alignment;
    $alignment = $index == 0 && strpos($layout, '-center-b') ? 'vertical' : $alignment;
    $alignment = $alignment ? "uk-margin-auto-{$alignment}" : '';
}

// determine special positions
if ($position == 'debug' || $position == 'navbar' && $module->type == 'menu') {

    if ($alignment) {
        echo "<div class=\"{$alignment}\">";
    }

    echo $module->content;

    if ($alignment) {
        echo '</div>';
    }

    return;
}

if ($position == 'navbar') {

    if ($module->type == 'search' && $theme->get('header.search_style') == 'modal' && preg_match('/^(horizontal|stacked)/', $layout)) {
        $itemClass = 'uk-navbar-toggle';
    } else {
        $itemClass = 'uk-navbar-item';
    }

    if ($toggle) {

        if ($alignment) {
            $class[] = $alignment;
        } else {
            $class[] = 'uk-margin-top';
        }

    } elseif ($layout == 'stacked-left-b' && $index == 1) {
        $class[] = "uk-margin-auto-left {$itemClass}";
    } else {
        $class[] = $itemClass;
    }

} elseif ($position == 'header' && preg_match('/^(offcanvas|modal|horizontal)/', $layout)) {

    $class[] = 'uk-navbar-item';

} elseif (in_array($position, ['header', 'mobile', 'toolbar-right', 'toolbar-left'])) {

    $class[] = 'uk-panel';

} else {

    $class[] = $module->config->get('style') ? "uk-card uk-card-body uk-{$module->config->get('style')}" : 'uk-panel';

}

// Class
if ($cls = (array) $module->config->get('class')) {
    $class = array_merge($class, $cls);
}

// Visibility
if ($visibility = $module->config->get('visibility')) {
    $class[] = "uk-visible@{$visibility}";
}

// Grid + sidebar positions
if (!preg_match('/^(toolbar-left|toolbar-right|navbar|header|debug)$/', $position)) {

    // Title?
    if ($module->config->get('showtitle')) {

        $title['class'] = [];

        $title_element = $module->config->get('title_tag', 'h3');

        // Style?
        $title['class'][] = $module->config->get('title_style') ? "uk-{$module->config->get('title_style')}" : '';
        $title['class'][] = $module->config->get('style') && !$module->config->get('title_style') ? 'uk-card-title' : '';

        // Decoration?
        $title['class'][] = $module->config->get('title_decoration') ? "uk-heading-{$module->config->get('title_decoration')}" : '';

    }

    // Text alignment
    if ($module->config->get('text_align') && $module->config->get('text_align') != 'justify' && $module->config->get('text_align_breakpoint')) {
        $class[] = "uk-text-{$module->config->get('text_align')}@{$module->config->get('text_align_breakpoint')}";
        if ($module->config->get('text_align_fallback')) {
            $class[] = "uk-text-{$module->config->get('text_align_fallback')}";
        }
    } elseif ($module->config->get('text_align')) {
        $class[] = "uk-text-{$module->config->get('text_align')}";
    }

    // List
    if ($module->config->get('is_list')) {
        $class[] = 'tm-child-list';

        // List Style?
        if ($module->config->get('list_style')) {
            $class[] = "tm-child-list-{$module->config->get('list_style')}";
        }

        // Link Style?
        if ($module->config->get('link_style')) {
            $class[] = "uk-link-{$module->config->get('link_style')}";
        }
    }

}

// Grid positions
if (preg_match('/^(top|bottom|builder-\d+)$/', $position)) {

    // Max Width?
    if ($module->config->get('maxwidth')) {
        $class[] = "uk-width-{$module->config->get('maxwidth')}";

        // Center?
        if ($module->config->get('maxwidth_align')) {
            $class[] = 'uk-margin-auto';
        }

    }

}

?>

<div<?= $this->attrs(compact('class'), $module->attrs) ?>>

    <?php if ($title) : ?>
    <<?= $title_element ?><?= $this->attrs($title) ?>>

        <?php if ($module->config->get('title_decoration') == 'line') : ?>
            <span><?= $module->title ?></span>
        <?php else: ?>
            <?= $module->title ?>
        <?php endif ?>

    </<?= $title_element ?>>
    <?php endif ?>

    <?= $module->content ?>

</div>
