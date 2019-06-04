<?php

defined('_JEXEC') or die;

$message = $module->content ? '<div class="uk-text-danger">Builder only supported on "top" and "bottom"</div>' : '';

echo "<div id=\"module-{$module->id}\" class=\"builder\">{$message}<!-- {$module->content} --></div>";
