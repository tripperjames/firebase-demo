<?php

$el = $this->el('div');

// Subnav
$subnav = $this->el('ul', [

    'class' => [
        'uk-subnav uk-margin-remove-bottom [uk-subnav-{subnav_style}]',
        'uk-flex-{text_align}[@{text_align_breakpoint} [uk-flex-{text_align_fallback}]]',
    ],

    'uk-margin' => true,
]);

?>

<?= $el($props, $attrs) ?>

    <?= $subnav($props) ?>
    <?php foreach ($children as $child) : ?>
    <li class="el-item"><?= $builder->render($child, ['element' => $props]) ?></li>
    <?php endforeach ?>
    </ul>

</div>
