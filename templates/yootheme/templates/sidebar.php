<?php

$sidebar = $theme->get('sidebar', []);
$id = 'tm-sidebar';
$class = ["tm-sidebar uk-width-{$sidebar['width']}@{$sidebar['breakpoint']}"];

if ($sidebar['first']) {
    $class[] = "uk-flex-first@{$sidebar['breakpoint']}";
}

?>

<aside<?= $this->attrs(compact('id', 'class')) ?>>
    <jdoc:include type="modules" name="sidebar" style="grid-stack" />
</aside>

