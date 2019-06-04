<?php
/*
 * The template for displaying uncategorized articles.
 */

?>

<article id="article-<?= $article->id ?>" class="uk-article"<?= $this->attrs(['data-permalink' => $permalink]) ?> typeof="Article">

    <meta property="name" content="<?= $this->e($title) ?>">
    <meta property="author" typeof="Person" content="<?= $this->e($article->author) ?>">
    <meta property="dateModified" content="<?= $this->date($article->modified, 'c') ?>">
    <meta property="datePublished" content="<?= $this->date($article->publish_up, 'c') ?>">
    <meta class="uk-margin-remove-adjacent" property="articleSection" content="<?= $this->e($article->category_title) ?>">

    <?php if ($image && $image->align == 'none') : ?>
    <div class="uk-margin-large-bottom" property="image" typeof="ImageObject">
        <?php if ($image->link) : ?>
        <a href="<?= $image->link ?>"><img<?= $this->attrs($image->attrs) ?> property="url"></a>
        <?php else : ?>
        <img<?= $this->attrs($image->attrs) ?> property="url">
        <?php endif ?>
    </div>
    <?php endif ?>

    <?php if ($title) : ?>
    <h1 class="uk-article-title"><?= $title ?></h1>
    <?php endif ?>

    <?php if ($author || $published || $category) : ?>
    <p class="uk-article-meta">
        <?php
            if ($author && $published) {
                JText::printf('TPL_YOOTHEME_META_AUTHOR_DATE', $author, $published);
            } elseif ($author) {
                JText::printf('TPL_YOOTHEME_META_AUTHOR', $author);
            } elseif ($published) {
                JText::printf('TPL_YOOTHEME_META_DATE', $published);
            }
        ?>
        <?= $category ? JText::sprintf('TPL_YOOTHEME_META_CATEGORY', $category) : '' ?>
    </p>
    <?php endif ?>

    <?php if ($event) echo $event->afterDisplayTitle ?>

    <?php if ($image && $image->align != 'none') : ?>
    <div class="<?= "uk-align-{$image->align}@s" ?>" property="image" typeof="ImageObject">
        <?php if ($image->link) : ?>
        <a href="<?= $image->link ?>"><img<?= $this->attrs($image->attrs) ?> property="url"></a>
        <?php else : ?>
        <img<?= $this->attrs($image->attrs) ?> property="url">
        <?php endif ?>
    </div>
    <?php endif ?>

    <?php if ($event) echo $event->beforeDisplayContent ?>

    <div class="uk-margin-medium" property="text"><?= $content ?></div>

    <?php if ($tags) : ?>
    <p class="uk-margin-medium"><?= JText::sprintf('TPL_YOOTHEME_TAGS', $tags) ?></p>
    <?php endif ?>

    <?php if ($readmore) : ?>
    <p class="uk-margin-medium">
        <a class="uk-button uk-button-text" href="<?= $readmore->link ?>"><?= $readmore->text ?></a>
    </p>
    <?php endif ?>

    <?php if ($created || $modified || $hits) : ?>
    <ul class="uk-list">

        <?php if ($created) : ?>
            <li><?= JText::sprintf('TPL_YOOTHEME_META_DATE_CREATED', $created) ?></li>
        <?php endif ?>

        <?php if ($modified) : ?>
            <li><?= JText::sprintf('TPL_YOOTHEME_META_DATE_MODIFIED', $modified) ?></li>
        <?php endif ?>

        <?php if ($hits) : ?>
            <li><?= JText::sprintf('TPL_YOOTHEME_META_HITS', $hits) ?></li>
        <?php endif ?>

    </ul>
    <?php endif ?>

    <?php if ($icons) : ?>
    <ul class="uk-subnav">
        <?php foreach ($icons as $icon) : ?>
        <li><?= $icon ?></li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>

    <?php if ($pagination) : ?>
    <ul class="uk-pagination uk-flex-between uk-margin-xlarge">

        <?php if ($pagination->prev) : ?>
        <li>
            <a href="<?= $pagination->prev ?>"><span uk-pagination-previous></span> <?= JText::_('JPREVIOUS') ?></a>
        </li>
        <?php endif ?>

        <?php if ($pagination->next) : ?>
        <li>
            <a href="<?= $pagination->next ?>"><?= JText::_('JNEXT') ?> <span uk-pagination-next></span></a>
        </li>
        <?php endif ?>

    </ul>
    <?php endif ?>

    <?php if ($event) echo $event->afterDisplayContent ?>

</article>
