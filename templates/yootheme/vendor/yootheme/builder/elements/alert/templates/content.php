<?php if ($props['title'] || $props['content']) : ?>
<div>

    <?php if ($props['title']) : ?>
    <h3><?= $props['title'] ?></h3>
    <?php endif ?>

    <?php if ($props['content']) : ?>
    <div><?= $props['content'] ?></div>
    <?php endif ?>

</div>
<?php endif ?>
