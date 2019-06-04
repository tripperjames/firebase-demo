<?php

// Title
if ($element['show_title'] && trim($props['title'])) {
    echo "<strong class=\"uk-display-block uk-margin\">{$props['title']}</strong>";
}

echo $props['content'];
