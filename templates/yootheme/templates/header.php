<?php

// Options
$layout = $theme->get('header.layout');
$fullwidth = $theme->get('header.fullwidth');
$logo_padding_remove = $fullwidth ? $theme->get('header.logo_padding_remove') : false;
$logo_center = $theme->get('header.logo_center');
$logo = $theme->get('logo.image') || $theme->get('logo.text');
$class = array_merge(['tm-header', 'uk-visible@'.$theme->get('mobile.breakpoint')], isset($class) ? (array) $class : []);
$attrs = array_merge(['uk-header' => true], isset($attrs) ? (array) $attrs : []);
$attrs_sticky = [];
$navbar = $theme->get('navbar', []);

// Container
$container = [];
$container['class'][] = 'uk-navbar-container';
$container['class'][] = $navbar['style'] ? "uk-navbar-{$navbar['style']}" : '';

// Dropdown options
if (!preg_match('/^(offcanvas|modal)/', $layout)) {

    $attrs_navbar = [
        'class' => 'uk-navbar',
        'uk-navbar' => json_encode(array_filter([
            'align' => $navbar['dropdown_align'],
            'boundary' => '!.uk-navbar-container',
            'boundary-align' => $navbar['dropdown_boundary'],
            'dropbar' => $navbar['dropbar'] ? true : null,
            'dropbar-anchor' => $navbar['dropbar'] ? '!.uk-navbar-container' : null,
            'dropbar-mode' => $navbar['dropbar'],
        ])),
    ];

} else {

    $attrs_navbar = [
        'class' => 'uk-navbar',
        'uk-navbar' => true,
    ];

}

// Sticky
if ($sticky = $navbar['sticky']) {

    $attrs_sticky = array_filter([
        'uk-sticky' => true,
        'media' => "@{$theme->get('mobile.breakpoint')}",
        'show-on-up' => $sticky == 2,
        'animation' => $sticky == 2 ? 'uk-animation-slide-top' : '',
        'cls-active' => 'uk-navbar-sticky',
        'sel-target' => '.uk-navbar-container',
    ]);

}

// Outside
$outside = $theme->get('site.layout') == 'boxed' && $theme->get('site.boxed.header_outside');

if ($outside && $theme->get('site.boxed.header_transparent')) {

    $class[] = 'tm-header-transparent';
    
    if ($sticky) {
        $attrs_sticky['cls-inactive'] = "uk-navbar-transparent uk-{$theme->get('site.boxed.header_transparent')}";
        $attrs_sticky['top'] = '300';
        if ($sticky == 1) {
            $attrs_sticky['animation'] = 'uk-animation-slide-top';
        }
    } else {
        $container['class'][] = "uk-navbar-transparent uk-{$theme->get('site.boxed.header_transparent')}";
    }
    
}

?>

<div class="tm-header-mobile uk-hidden@<?= $theme->get('mobile.breakpoint') ?>">
<?= $this->render('header-mobile') ?>
</div>

<?php if (!$theme->get('site.toolbar_transparent') && ($this->countModules('toolbar-left') || $this->countModules('toolbar-right'))) : ?>
<?= $this->render('toolbar') ?>
<?php endif ?>

<div<?= $this->attrs(['class' => $class], $attrs) ?>>

<?php if ($theme->get('site.toolbar_transparent') && ($this->countModules('toolbar-left') || $this->countModules('toolbar-right'))) : ?>
<?= $this->render('toolbar') ?>
<?php endif ?>

<?php

/*
 * Horizontal layouts
 */

if (in_array($layout, ['horizontal-left', 'horizontal-center', 'horizontal-right', 'horizontal-center-logo'])) : ?>

    <?php if ($sticky) : ?>
    <div<?= $this->attrs($attrs_sticky) ?>>
    <?php endif ?>

        <div<?= $this->attrs($container) ?>>

            <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : ($outside ? ' tm-page-width' : '') ?><?= $logo && $logo_padding_remove && $layout != 'horizontal-center-logo' ? ' uk-padding-remove-left' : '' ?>">
                <nav<?= $this->attrs($attrs_navbar) ?>>

                    <?php if (($logo && $layout != 'horizontal-center-logo') || (in_array($layout, ['horizontal-left', 'horizontal-center-logo']) && $this->countModules('navbar'))) : ?>
                    <div class="uk-navbar-left">

                        <?php if ($logo && $layout != 'horizontal-center-logo') : ?>
                            <?= $logo ? $this->render('header-logo', ['class' => 'uk-navbar-item']) : '' ?>
                        <?php endif ?>

                        <?php if (in_array($layout, ['horizontal-left', 'horizontal-center-logo']) && $this->countModules('navbar')) : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if (($logo && $layout == 'horizontal-center-logo') || ($layout == 'horizontal-center' && $this->countModules('navbar'))) : ?>
                    <div class="uk-navbar-center">

                        <?php if ($logo && $layout == 'horizontal-center-logo') : ?>
                            <?= $logo ? $this->render('header-logo', ['class' => 'uk-navbar-item']) : '' ?>
                        <?php endif ?>

                        <?php if ($layout == 'horizontal-center' && $this->countModules('navbar')) : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if ($this->countModules('header') || $layout == 'horizontal-right' && $this->countModules('navbar')) : ?>
                    <div class="uk-navbar-right">

                        <?php if ($layout == 'horizontal-right' && $this->countModules('navbar')) : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                        <jdoc:include type="modules" name="header" />

                    </div>
                    <?php endif ?>

                </nav>
            </div>

        </div>

    <?php if ($sticky) : ?>
    </div>
    <?php endif ?>

<?php endif ?>

<?php

/*
 * Stacked Center layouts
 */

if (in_array($layout, ['stacked-center-a', 'stacked-center-b', 'stacked-center-split'])) : ?>

    <?php if ($logo && $layout != 'stacked-center-split' || $layout == 'stacked-center-a' && $this->countModules('header')) : ?>
    <div class="tm-headerbar-top<?= $outside && $theme->get('site.boxed.header_transparent') ? " uk-{$theme->get('site.boxed.header_transparent')}" : ''; ?>">
        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : ($outside ? ' tm-page-width' : '') ?>">

            <?php if ($logo) : ?>
            <div class="uk-text-center">
                <?= $this->render('header-logo') ?>
            </div>
            <?php endif ?>

            <?php if ($layout == 'stacked-center-a' && $this->countModules('header')) : ?>
            <div class="tm-headerbar-stacked uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle" uk-grid>
                <jdoc:include type="modules" name="header" style="cell" />
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($this->countModules('navbar')) : ?>

        <?php if ($sticky) : ?>
        <div<?= $this->attrs($attrs_sticky) ?>>
        <?php endif ?>

            <div<?= $this->attrs($container) ?>>

                <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : ($outside ? ' tm-page-width' : '') ?>">
                    <nav<?= $this->attrs($attrs_navbar) ?>>

                        <div class="uk-navbar-center">

                            <?php if ($layout == 'stacked-center-split') : ?>

                                <div class="uk-navbar-center-left uk-preserve-width"><div>
                                    <jdoc:include type="modules" name="navbar-split" />
                                </div></div>

                                <?= $this->render('header-logo', ['class' => 'uk-navbar-item']) ?>

                                <div class="uk-navbar-center-right uk-preserve-width"><div>
                                    <jdoc:include type="modules" name="navbar" />
                                </div></div>

                            <?php else: ?>
                                <jdoc:include type="modules" name="navbar" />
                            <?php endif ?>

                        </div>

                    </nav>
                </div>

            </div>

        <?php if ($sticky) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

    <?php if (in_array($layout, ['stacked-center-b', 'stacked-center-split']) && $this->countModules('header')) : ?>
    <div class="tm-headerbar-bottom<?= $outside && $theme->get('site.boxed.header_transparent') ? " uk-{$theme->get('site.boxed.header_transparent')}" : ''; ?>">
        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : ($outside ? ' tm-page-width' : '') ?>">
            <div class="uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle" uk-grid>
                <jdoc:include type="modules" name="header" style="cell" />
            </div>
        </div>
    </div>
    <?php endif ?>

<?php endif ?>

<?php

/*
 * Stacked Left layouts
 */

if ($layout == 'stacked-left-a' || $layout == 'stacked-left-b') : ?>

    <?php if ($logo || $this->countModules('header')) : ?>
    <div class="tm-headerbar-top<?= $outside && $theme->get('site.boxed.header_transparent') ? " uk-{$theme->get('site.boxed.header_transparent')}" : ''; ?>">
        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : ($outside ? ' tm-page-width' : '') ?> uk-flex uk-flex-middle">

            <?= $logo ? $this->render('header-logo') : '' ?>

            <?php if ($this->countModules('header')) : ?>
            <div class="uk-margin-auto-left">
                <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid>
                    <jdoc:include type="modules" name="header" style="cell" />
                </div>
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($this->countModules('navbar')) : ?>

        <?php if ($sticky) : ?>
        <div<?= $this->attrs($attrs_sticky) ?>>
        <?php endif ?>

            <div<?= $this->attrs($container) ?>>

                <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : ($outside ? ' tm-page-width' : '') ?>">
                    <nav<?= $this->attrs($attrs_navbar) ?>>

                        <?php if ($layout == 'stacked-left-a') : ?>
                        <div class="uk-navbar-left">
                            <jdoc:include type="modules" name="navbar" />
                        </div>
                        <?php endif ?>

                        <?php if ($layout == 'stacked-left-b') : ?>
                        <div class="uk-navbar-left uk-flex-auto">
                            <jdoc:include type="modules" name="navbar" />
                        </div>
                        <?php endif ?>

                    </nav>
                </div>

            </div>

        <?php if ($sticky) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

<?php endif ?>

<?php

/*
 * Toggle layouts
 */

if (preg_match('/^(offcanvas|modal)/', $layout)) :

    $attrs_toggle = [];

    $attrs_toggle['class'][] = strpos($layout, 'modal') === 0 ? 'uk-modal-body uk-padding-large uk-margin-auto uk-height-viewport' : 'uk-offcanvas-bar';
    $attrs_toggle['class'][] = $navbar['toggle_menu_center'] ? 'uk-text-center' : '';
    $attrs_toggle['class'][] = 'uk-flex uk-flex-column';

    if ($logo_center) {
        $logo_padding_remove = false;
    }

    ?>

    <?php if ($sticky) : ?>
    <div<?= $this->attrs($attrs_sticky) ?>>
    <?php endif ?>

        <div<?= $this->attrs($container) ?>>
            <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : ($outside ? ' tm-page-width' : '') ?><?= $logo && $logo_padding_remove ? ' uk-padding-remove-left' : '' ?>">
                <nav<?= $this->attrs($attrs_navbar) ?>>

                    <?php if ($logo) : ?>
                    <div class="<?= $logo_center ? 'uk-navbar-center' : 'uk-navbar-left' ?>">
                        <?= $this->render('header-logo', ['class' => 'uk-navbar-item']) ?>
                    </div>
                    <?php endif ?>

                    <?php if ($this->countModules('header') || $this->countModules('navbar')) : ?>
                    <div class="uk-navbar-right">

                        <jdoc:include type="modules" name="header" />

                        <?php if ($this->countModules('navbar')) : ?>

                            <a class="uk-navbar-toggle" href="#tm-navbar" uk-toggle>
                                <?php if ($navbar['toggle_text']) : ?>
                                <span class="uk-margin-small-right"><?= JText::_('TPL_YOOTHEME_MENU') ?></span>
                                <?php endif ?>
                                <div uk-navbar-toggle-icon></div>
                            </a>

                            <?php if (strpos($layout, 'offcanvas') === 0) : ?>
                            <div id="tm-navbar" uk-offcanvas="flip: true"<?= $this->attrs($navbar['offcanvas'] ?: []) ?>>
                                <div<?= $this->attrs($attrs_toggle) ?>>

                                    <button class="uk-offcanvas-close uk-close-large uk-margin-remove-adjacent" type="button" uk-close></button>

                                    <jdoc:include type="modules" name="navbar" />

                                </div>
                            </div>
                            <?php endif ?>

                            <?php if (strpos($layout, 'modal') === 0) : ?>
                            <div id="tm-navbar" class="uk-modal-full" uk-modal>
                                <div class="uk-modal-dialog uk-flex">

                                    <button class="uk-modal-close-full uk-close-large uk-margin-remove-adjacent" type="button" uk-close></button>

                                    <div <?= $this->attrs($attrs_toggle) ?>>
                                        <jdoc:include type="modules" name="navbar" />
                                    </div>

                                </div>
                            </div>
                            <?php endif ?>

                        <?php endif ?>

                    </div>
                    <?php endif ?>

                </nav>
            </div>
        </div>

    <?php if ($sticky) : ?>
    </div>
    <?php endif ?>

<?php endif ?>

</div>
