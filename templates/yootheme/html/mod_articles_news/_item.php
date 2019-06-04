<?php

defined('_JEXEC') or die;

?>

<?php if ($params->get('item_title')) : ?>
<h3>
    <?php if ($params->get('link_titles') && $item->link != '') : ?>
        <a href="<?= $item->link ?>"><?= $item->title ?></a>
    <?php else : ?>
        <?= $item->title ?>
    <?php endif ?>
</h3>
<?php endif ?>

<?php if (!$params->get('intro_only')) echo $item->afterDisplayTitle ?>

<?= $item->beforeDisplayContent ?>
<?= $item->introtext ?>
<?= $item->afterDisplayContent ?>

<?php if (isset($item->link) && $item->readmore && $params->get('readmore')) : ?>
<p><a class="uk-button uk-button-text" href="<?= $item->link ?>"><?= $item->linkText ?></a></p>
<?php endif ?>
