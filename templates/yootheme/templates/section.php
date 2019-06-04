<?php

// Empty ?
if (!$this->countModules($name) or !$section = $theme->get($name)) {
    return;
}

// Deprecated
if ($section['vertical_align'] === true) {
    $section['vertical_align'] = 'middle';
}
if ($section['style'] == 'video') {
    $section['style'] = 'default';
}

$id = "tm-{$name}";
$class = [$id];
$style = [];
$attrs = [
    'tm-header-transparent' => $section['header_transparent'] ? $section['header_transparent'] : false,
    'tm-header-transparent-placeholder' => $section['header_transparent'] && !$section['header_transparent_noplaceholder']
];
$attrs_overlay = [];
$attrs_container = [];
$attrs_viewport_height = [];
$attrs_image = [];
$attrs_video = [];
$attrs_section = [];

// Section
$class[] = "uk-section-{$section['style']}";
$class[] = $section['overlap'] ? 'uk-section-overlap' : '';
$attrs_section['class'][] = 'uk-section';

// Image
if ($section['image']) {

    $attrs_image = $this->bgImage($section['image'], [
        'width' => $section['image_width'],
        'height' => $section['image_height'],
        'size' => $section['image_size'],
        'position' => $section['image_position'],
        'visibility' => $section['image_visibility'],
        'blend_mode' => $section['media_blend_mode'],
        'background' => $section['media_background'],
        'effect' => $section['image_fixed'] ? 'fixed' : false,
    ]);

    // Overlay
    if ($section['media_overlay']) {
        $class[] = 'uk-position-relative';
        $attrs_overlay['style'] = "background-color: {$section['media_overlay']};";
    }

}

// Video
if ($section['video'] && !$section['image']) {

    // Settings
    $style[] = $section['media_background'] ? "background-color: {$section['media_background']};" : '';
    $attrs_video['class'][] = $section['media_blend_mode'] ? "uk-blend-{$section['media_blend_mode']}" : '';

    // Cover
    $class[] = 'uk-cover-container';
    $attrs_video['uk-cover'] = true;

    // Overlay
    $attrs_overlay['style'] = $section['media_overlay'] ? "background-color: {$section['media_overlay']};" : '';

    // Video
    $attrs_video['width'] = $section['video_width'];
    $attrs_video['height'] = $section['video_height'];

    if ($iframe = $this->iframeVideo($section['video'])) {

        $attrs_video['src'] = $iframe;
        $attrs_video['frameborder'] = '0';
        $attrs_video['allowfullscreen'] = true;

        $section['video'] = "<iframe{$this->attrs($attrs_video)}></iframe>";

    } else if ($section['video']) {

        $attrs_video['src'] = $section['video'];
        $attrs_video['controls'] = false;
        $attrs_video['loop'] = true;
        $attrs_video['autoplay'] = true;
        $attrs_video['muted'] = true;
        $attrs_video['playsinline'] = true;

        $section['video'] = "<video{$this->attrs($attrs_video)}></video>";
    }

} else {
    $section['video'] = '';
}

// Text color
if ($section['style'] == 'primary' || $section['style'] == 'secondary') {
    $class[] = $section['preserve_color'] ? 'uk-preserve-color' : '';
} elseif ($section['image'] || $section['video']) {
    $class[] = $section['text_color'] ? "uk-{$section['text_color']}" : '';
}

// Padding
switch ($section['padding']) {
    case '':
        break;
    case 'none':
        $attrs_section['class'][] = 'uk-padding-remove-vertical';
        break;
    default:
        $attrs_section['class'][] = "uk-section-{$section['padding']}";
}

if ($section['padding'] != 'none') {
    if ($section['padding_remove_top']) {
        $attrs_section['class'][] = 'uk-padding-remove-top';
    }
    if ($section['padding_remove_bottom']) {
        $attrs_section['class'][] = 'uk-padding-remove-bottom';
    }
}

// Height Viewport
if ($section['height']) {

    if ($section['height'] == 'expand') {
        $attrs_section['uk-height-viewport'] = 'expand: true';
    } else {

        // Vertical alignment
        if ($section['vertical_align']) {
            $attrs_section['class'][] = "uk-flex uk-flex-{$section['vertical_align']}";
            $attrs_viewport_height['class'][] = 'uk-width-1-1';
        }

        $options = ['offset-top: true'];
        switch ($section['height']) {
            case 'percent':
                $options[] = 'offset-bottom: 20';
                break;
            case 'section':
                $options[] = $section['image'] ? 'offset-bottom: ! +' : 'offset-bottom: true';
                break;
        }

        $attrs_section['uk-height-viewport'] = implode(';', array_filter($options));

    }

}

// Container and width
switch ($section['width']) {
    case 'default':
        $attrs_container['class'][] = 'uk-container';
        break;
    case 'small':
    case 'large':
    case 'expand':
        $attrs_container['class'][] = "uk-container uk-container-{$section['width']}";
        break;
    // Deprecated
    case 1:
        $attrs_container['class'][] = 'uk-container';
        break;
    case 2:
        $attrs_container['class'][] = 'uk-container uk-container-small';
        break;
    case 3:
        $attrs_container['class'][] = 'uk-container uk-container-expand';
}

// Make sure overlay and video is always below content
if ($attrs_overlay || $section['video']) {
    $attrs_container['class'][] = $section['width'] ? 'uk-position-relative' : 'uk-position-relative uk-panel';
}

?>

<div<?= $this->attrs(compact('id', 'class', 'style'), $attrs, !$attrs_image ? $attrs_section : []) ?>>

    <?php if ($attrs_image) : ?>
    <div<?= $this->attrs($attrs_image, $attrs_section) ?>>
    <?php endif ?>

        <?= $section['video'] ?>

        <?php if ($attrs_overlay) : ?>
        <div class="uk-position-cover"<?= $this->attrs($attrs_overlay) ?>></div>
        <?php endif ?>

        <?php if ($attrs_viewport_height) : ?>
        <div<?= $this->attrs($attrs_viewport_height) ?>>
        <?php endif ?>

            <?php if ($attrs_container) : ?>
            <div<?= $this->attrs($attrs_container) ?>>
            <?php endif ?>

                <?= $this->render('position', ['style' => 'grid']) ?>

            <?php if ($attrs_container) : ?>
            </div>
            <?php endif ?>

        <?php if ($attrs_viewport_height) : ?>
        </div>
        <?php endif ?>

    <?php if ($attrs_image) : ?>
    </div>
    <?php endif ?>

</div>
