<?php if ($props['link']) : ?>
<a href="<?= $props['link'] ?>">
<?php endif ?>

    <?php if ($props['image']) : ?>
    <img src="<?= $props['image'] ?>" alt="<?= $props['image_alt'] ?>">
    <?php elseif ($props['video']) : ?>

    <?php if ($this->iframeVideo($props['video'], [], false)) : ?>
    <iframe src="<?= $props['video'] ?>"></iframe>
    <?php else : ?>
    <video src="<?= $props['video'] ?>">
    <?php endif ?>

    <?php endif ?>

<?php if ($props['link']) : ?>
</a>
<?php endif ?>

<?php if ($props['title']) : ?>
<<?= $element['title_element'] ?>><?= $props['title'] ?></<?= $element['title_element'] ?>>
<?php endif ?>

<?php if ($props['meta']) : ?>
<p><?= $props['meta'] ?></p>
<?php endif ?>

<?php if ($props['content']) : ?>
<div><?= $props['content'] ?></div>
<?php endif ?>
