<?php

$links = array_filter(!empty($props['links']) ? (array) $props['links'] : []);

?>

<?php if (count($links) > 1) : ?>
<ul>

    <?php foreach ($links as $link) : ?>
    <li><a href="<?= $link ?>"><?= $this->e($link, 'social') ?></a></li>
    <?php endforeach ?>

</ul>
<?php elseif (count($links) == 1) : ?>
<p>
    <a href="<?= $links[0] ?>"><?= strtoupper($this->e($links[0], 'social')) ?></a>
</p>
<?php endif ?>
