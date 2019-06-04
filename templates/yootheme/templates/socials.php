<?php

$links = $theme->get('social_links', [])->splice(0, 5)->filter();

$attrs['class'] = $theme->get('header.social_style') ? 'uk-icon-button' : 'uk-icon-link';

// Grid
$attrs_grid = [];
$attrs_grid['class'][] = 'uk-grid-small uk-flex-inline uk-flex-middle uk-flex-nowrap';
$attrs_grid['uk-grid'] = true;

?>

<?php if (count($links)) : ?>
    <ul<?= $this->attrs($attrs_grid) ?>>
        <?php foreach ($links as $link) :
            $attrs['target'] = $theme->get('header.social_target') && (preg_match('/(tel:|mailto:)/', $link) == 0) ? '_blank' : false;
            ?>
            <li>
                <a<?= $this->attrs(['href' => $link], $attrs) ?> uk-icon="<?= $this->e($link, 'social') ?>"></a>
            </li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
