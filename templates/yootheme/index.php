<?php

defined('_JEXEC') or die();

$theme = JHtml::_('theme');

// Set HTML5 output
$this->setHtml5(true);

// Set view
$input = JFactory::getApplication()->input;
$view = '';

if (in_array($input->get('option'), ['com_content', 'com_tags'])) {

    if (in_array($input->get('view'), ['category', 'featured', 'tag'])) {
        $view = 'blog';
    } elseif (($input->get('view') == 'article') && ($input->get('layout') == 'post')) {
        // article category has to be 'uncategorized', 'layout' is set in html/com_content/article/default.php
        $view = 'post';
    }

}

// Parameter shortcuts
$site  = $theme->get('site', []);
$blog_settings  = $theme->get('blog', []);
$post_settings  = $theme->get('post', []);

// Deprecated
if ($site['boxed.padding'] && !isset($site['boxed.margin_top'], $site['boxed.margin_bottom'])) {
    $site['boxed.margin_top'] = true;
    $site['boxed.margin_bottom'] = true;
}

// Page
$attrs_page = [];
$attrs_page_container = [];
$attrs_image = [];
$attrs_media_overlay = [];

$attrs_page['class'][] = 'tm-page';

if ($site['layout'] == 'boxed') {

    $attrs_page['class'][] = $site['boxed.alignment'] ? 'uk-margin-auto' : '';
    $attrs_page['class'][] = $site['boxed.margin_top'] ? 'tm-page-margin-top' : '';
    $attrs_page['class'][] = $site['boxed.margin_bottom'] ? 'tm-page-margin-bottom' : '';
    $attrs_page_container['class'][] = 'tm-page-container uk-clearfix';

    // Image
    if ($site['boxed.media']) {
   
        $attrs_image = $theme->app['view']->bgImage($site['boxed.media'], [
            'width' => $site['image_width'],
            'height' => $site['image_height'],
            'size' => $site['image_size'],
            'position' => $site['image_position'],
            'visibility' => $site['image_visibility'],
            'blend_mode' => $site['media_blend_mode'],
            'background' => $site['media_background'],
            'effect' => $site['image_effect'],
            'parallax_bgx_start' => $site['image_parallax_bgx_start'],
            'parallax_bgy_start' => $site['image_parallax_bgy_start'],
            'parallax_bgx_end' => $site['image_parallax_bgx_end'],
            'parallax_bgy_end' => $site['image_parallax_bgy_end'],
            'parallax_breakpoint' => $site['image_parallax_breakpoint'],
            'parallax_target' => 'body',
        ]);

        if ($site['image_effect']) {
            $attrs_image['class'][] = 'uk-position-cover uk-position-fixed';
        } else {
            $attrs_page_container = array_merge_recursive($attrs_page_container, $attrs_image);
            $attrs_image = [];
        }

        // Overlay
        if ($site['media_overlay']) {
            $attrs_page_container['class'][] = 'uk-position-relative';
            $attrs_media_overlay['class'][] = 'uk-position-cover';
            $attrs_media_overlay['style'] = "background-color: {$site['media_overlay']};";
        }

    }

}

// Main section
$attrs_main_section = [];
$attrs_main_section['class'][] = 'tm-main uk-section uk-section-default';
$attrs_main_section['class'][] = $view == 'blog' && $blog_settings['padding'] ? "uk-section-{$blog_settings['padding']}" : '';
$attrs_main_section['class'][] = $view == 'post' && $post_settings['padding'] ? "uk-section-{$post_settings['padding']}" : '';
$attrs_main_section['class'][] = $view == 'post' && $post_settings['padding_remove'] ? 'uk-padding-remove-top' : '';

// Main container
$attrs_main_container = [];

if ($view !== 'post' || ($view == 'post' && $post_settings['width'] != 'none')) {
    $attrs_main_container['class'][] = 'uk-container';
    $attrs_main_container['class'][] = $view == 'blog' && $blog_settings['width'] ? "uk-container-{$blog_settings['width']}" : '';
    $attrs_main_container['class'][] = $view == 'post' && $post_settings['width'] ? "uk-container-{$post_settings['width']}" : '';
}

?>
<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>" vocab="http://schema.org/">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?= $theme->get('favicon') ?>">
        <link rel="apple-touch-icon-precomposed" href="<?= $theme->get('touchicon') ?>">
        <jdoc:include type="head" />
    </head>
    <body class="<?= $theme->get('body_class')->join(' ') ?>">

        <?php if ($site['layout'] == 'boxed') : ?>
        <div<?= JHtml::_('attrs', $attrs_page_container) ?>>

            <?php if ($attrs_image) : ?>
            <div<?= JHtml::_('attrs', $attrs_image) ?>></div>
            <?php endif ?>

            <?php if ($attrs_media_overlay) : ?>
            <div class="uk-position-cover"<?= JHtml::_('attrs', $attrs_media_overlay) ?>></div>
            <?php endif ?>

        <?php endif ?>

        <?php if ($site['layout'] == 'boxed' && $site['boxed.header_outside']) : ?>
        <?= JHtml::_('render', 'header') ?>
        <?php endif ?>

        <div<?= JHtml::_('attrs', $attrs_page) ?>>

            <?php if (!($site['layout'] == 'boxed' && $site['boxed.header_outside'])) : ?>
            <?= JHtml::_('render', 'header') ?>
            <?php endif ?>

            <jdoc:include type="modules" name="top" style="section" />

            <?php if (!$theme->get('builder')) : ?>

            <div id="tm-main" <?= JHtml::_('attrs', $attrs_main_section) ?> uk-height-viewport="expand: true">
                <div<?= JHtml::_('attrs', $attrs_main_container) ?>>

                    <?php if ($this->countModules('sidebar')) :
                            $sidebar = $theme->get('sidebar', []);
                            $grid = ['uk-grid'];
                            $grid[] = $sidebar['gutter'] ? "uk-grid-{$sidebar['gutter']}" : '';
                            $grid[] = $sidebar['divider'] ? 'uk-grid-divider' : '';
                    ?>

                    <div<?= JHtml::_('attrs', ['class' => $grid, 'uk-grid' => true]) ?>>
                        <div class="uk-width-expand@<?= $theme->get('sidebar.breakpoint') ?>">

                    <?php endif ?>

                            <?php if ($site['breadcrumbs']) : ?>
                            <div class="uk-margin-medium-bottom">
                                <?= JHtml::_('section', 'breadcrumbs') ?>
                            </div>
                            <?php endif ?>

            <?php endif ?>

            <jdoc:include type="message" />
            <jdoc:include type="component" />

            <?= JHtml::_('section', 'builder') ?>

            <?php if (!$theme->get('builder')) : ?>

                        <?php if ($this->countModules('sidebar')) : ?>
                        </div>

                        <?= JHtml::_('render', 'sidebar') ?>

                    </div>
                     <?php endif ?>

                </div>
            </div>
            <?php endif ?>

            <jdoc:include type="modules" name="bottom" style="section" />

            <?= JHtml::_('builder', json_encode($theme->get('footer.content')), ['prefix' => 'footer']) ?>

        </div>

        <?php if ($site['layout'] == 'boxed') : ?>
        </div>
        <?php endif ?>

        <jdoc:include type="modules" name="debug" />

    </body>
</html>
