<?php
/*
 * The template for displaying articles on the search page.
 */

?>

<article class="uk-article">

    <?php if ($title) : ?>
    <h1 class="uk-article-title"><?= $title ?></h1>
    <?php endif ?>

    <?= $content ?>

</article>
