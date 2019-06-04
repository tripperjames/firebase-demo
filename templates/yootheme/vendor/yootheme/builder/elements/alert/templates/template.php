<?php

$el = $this->el('div', [

    'class' => [
        'uk-alert',
        'uk-alert-{alert_style}',
        'uk-padding {@alert_size}',
    ]

]);

?>

<?= $el($props, $attrs) ?>

    <?php if ($props['title']) : ?>
    <h3 class="el-title"><?= $props['title'] ?></h3>
    <?php endif ?>

    <div class="el-content"><?= $props['content'] ?></div>

</div>
