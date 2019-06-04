<?php

$config = $theme->get('logo', []);
$mobile = $theme->get('mobile', []);
$attrs_navbar = [
    'class' => 'uk-navbar-container',
    'uk-navbar' => true,
];
$attrs_sticky = [];
$attrs_image = [];
$attrs_menu = [];

// Sticky
if ($sticky = $mobile['sticky']) {
    $attrs_sticky = array_filter([
        'uk-sticky' => true,
        'show-on-up' => $sticky == 2,
        'animation' => $sticky == 2 ? 'uk-animation-slide-top' : '',
        'cls-active' => 'uk-navbar-sticky',
        'sel-target' => '.uk-navbar-container',
    ]);
}

// Logo Text
$logo = $config['text'];
$logo = JText::_($logo);

// Image Fallback
if ($config['image_mobile']) {
    $config['image'] = $config['image_mobile'];
    $config['image_width'] = $config['image_mobile_width'];
    $config['image_height'] = $config['image_mobile_height'];
}

// Image
if ($config['image']) {

    $attrs_image['alt'] = $config['text'];
    $attrs_image['uk-gif'] = $this->isImage($config['image']) == 'gif';

    if ($this->isImage($config['image']) == 'svg') {
        $logo = $this->image($config['image'], array_merge($attrs_image, ['width' => $config['image_width'], 'height' => $config['image_height']]));
    } else {
        $logo = $this->image([$config['image'], 'thumbnail' => [$config['image_width'], $config['image_height']], 'srcset' => true], $attrs_image);
    }

}

if (!$logo) {
    unset($mobile['logo']);
}

if (!$this->countModules('mobile')) {
    unset($mobile['toggle']);
}

$mobile['search'] = false; // TODO

// Mobile Position
if ($this->countModules('mobile')) {

    $attrs_menu['class'][] = $mobile['animation'] == 'offcanvas' ? 'uk-offcanvas-bar' : '';
    $attrs_menu['class'][] = $mobile['animation'] == 'modal' ? 'uk-modal-dialog uk-modal-body' : '';
    $attrs_menu['class'][] = $mobile['animation'] == 'dropdown' ? 'uk-background-default uk-padding' : '';
    $attrs_menu['class'][] = $mobile['menu_center'] ? 'uk-text-center' : '';
    $attrs_menu['class'][] = $mobile['animation'] != 'dropdown' && $mobile['menu_center_vertical'] ? 'uk-flex' : '';

    $mobile->set('offcanvas.overlay', true);

} else {
    $mobile['animation'] = false;
}

?>

<?php if ($sticky) : ?>
<div<?= $this->attrs($attrs_sticky) ?>>
<?php endif ?>

    <nav<?= $this->attrs($attrs_navbar) ?>>

        <?php if ($mobile['logo'] == 'left' || $mobile['toggle'] == 'left' || $mobile['search'] == 'left') : ?>
        <div class="uk-navbar-left">

            <?php if ($mobile['logo'] == 'left') : ?>
            <a class="uk-navbar-item uk-logo<?= $mobile['logo_padding_remove'] ? ' uk-padding-remove-left' : '' ?>" href="<?= $theme->get('site_url') ?>">
                <?= $logo ?>
            </a>
            <?php endif ?>

            <?php if ($mobile['toggle'] == 'left') : ?>
            <a class="uk-navbar-toggle" href="#tm-mobile" uk-toggle<?= ($mobile['animation'] == 'dropdown') ? '="animation: true"' : '' ?>>
                <div uk-navbar-toggle-icon></div>
                <?php if ($mobile['toggle_text']) : ?>
                    <span class="uk-margin-small-left"><?= JText::_('TPL_YOOTHEME_MENU') ?></span>
                <?php endif ?>
            </a>
            <?php endif ?>

            <?php if ($mobile['search'] == 'left') : ?>
            <a class="uk-navbar-item"><?= JText::_('TPL_YOOTHEME_SEARCH') ?></a>
            <?php endif ?>

        </div>
        <?php endif ?>

        <?php if ($mobile['logo'] == 'center') : ?>
        <div class="uk-navbar-center">
            <a class="uk-navbar-item uk-logo" href="<?= $theme->get('site_url') ?>">
                <?= $logo ?>
            </a>
        </div>
        <?php endif ?>

        <?php if ($mobile['logo'] == 'right' || $mobile['toggle'] == 'right' || $mobile['search'] == 'right') : ?>
        <div class="uk-navbar-right">

            <?php if ($mobile['search'] == 'right') : ?>
            <a class="uk-navbar-item"><?= JText::_('TPL_YOOTHEME_SEARCH') ?></a>
            <?php endif ?>

            <?php if ($mobile['toggle'] == 'right') : ?>
            <a class="uk-navbar-toggle" href="#tm-mobile" uk-toggle<?= $mobile['animation'] == 'dropdown' ? '="animation: true"' : '' ?>>
                <?php if ($mobile['toggle_text']) : ?>
                    <span class="uk-margin-small-right"><?= JText::_('TPL_YOOTHEME_MENU') ?></span>
                <?php endif ?>
                <div uk-navbar-toggle-icon></div>
            </a>
            <?php endif ?>

            <?php if ($mobile['logo'] == 'right') : ?>
            <a class="uk-navbar-item uk-logo<?= $mobile['logo_padding_remove'] ? ' uk-padding-remove-right' : '' ?>" href="<?= $theme->get('site_url') ?>">
                <?= $logo ?>
            </a>
            <?php endif ?>

        </div>
        <?php endif ?>

    </nav>

    <?php if ($mobile['animation'] == 'dropdown') : ?>

        <?php if ($mobile['dropdown'] == 'slide') : ?>
        <div class="uk-position-relative tm-header-mobile-slide">
        <?php endif ?>

        <div id="tm-mobile" class="<?= $mobile['dropdown'] == 'slide' ? 'uk-position-top' : '' ?>" hidden>
            <div<?= $this->attrs($attrs_menu) ?>>

                <jdoc:include type="modules" name="mobile" style="grid-stack" />

            </div>
        </div>

        <?php if ($mobile['dropdown'] == 'slide') : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

<?php if ($sticky) : ?>
</div>
<?php endif ?>

<?php if ($mobile['animation'] == 'offcanvas') : ?>
<div id="tm-mobile" uk-offcanvas<?= $this->attrs($mobile['offcanvas'] ?: []) ?>>
    <div<?= $this->attrs($attrs_menu) ?>>

        <button class="uk-offcanvas-close" type="button" uk-close></button>

        <?php if ($mobile['menu_center_vertical']) : ?>
        <div class="uk-margin-auto-vertical uk-width-1-1">
            <?php endif ?>

            <jdoc:include type="modules" name="mobile" style="grid-stack" />

            <?php if ($mobile['menu_center_vertical']) : ?>
        </div>
        <?php endif ?>

    </div>
</div>
<?php endif ?>

<?php if ($mobile['animation'] == 'modal') : ?>
<div id="tm-mobile" class="uk-modal-full" uk-modal>
    <div<?= $this->attrs($attrs_menu, ['class' => 'uk-height-viewport']) ?>>

        <button class="uk-modal-close-full" type="button" uk-close></button>

        <?php if ($mobile['menu_center_vertical']) : ?>
        <div class="uk-margin-auto-vertical uk-width-1-1">
            <?php endif ?>

            <jdoc:include type="modules" name="mobile" style="grid-stack" />

            <?php if ($mobile['menu_center_vertical']) : ?>
        </div>
        <?php endif ?>

    </div>
</div>
<?php endif ?>
