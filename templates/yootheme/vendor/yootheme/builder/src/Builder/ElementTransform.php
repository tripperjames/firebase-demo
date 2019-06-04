<?php

namespace YOOtheme\Builder;

class ElementTransform
{
    /**
     * Transform callback.
     *
     * @param object $node
     * @param array  $params
     */
    public function __invoke($node, array $params)
    {
        /**
         * @var $type
         */
        extract($params);

        if ($type->element || $type->container) {

            $node->attrs += [
                'id' => !empty($node->props['id']) ? $node->props['id'] : null,
                'class' => !empty($node->props['class']) ? [$node->props['class']] : [],
            ];

            $this->parallax($node, $params);
            $this->visibility($node, $params);
            $this->margin($node, $params);
            $this->maxWidth($node, $params);
            $this->textAlign($node, $params);
            $this->customCSS($node, $params);

            if ($type->element) {
                $this->animation($node, $params);
                $this->containerPadding($node, $params);
            }

        }
    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function animation($node, array $params)
    {
        /**
         * @var $builder
         * @var $path
         */
        extract($params);

        $svgAnimation = !empty($node->props['image_svg_inline']) && !empty($node->props['image_svg_animate']);
        if ($svgAnimation) {
            $node->props['image_svg_inline'] = ['stroke-animation: true; attributes: uk-scrollspy-class:uk-animation-stroke'];
        }

        $sectionAnimation = $builder->parent($path, 'section', 'animation');
        foreach (isset($node->props['item_animation']) ? $node->children : [$node] as $child) {

            $animation = @$child->props['item_animation'] ?: @$child->props['animation'];

            if ($sectionAnimation) {
                $child->attrs['uk-scrollspy-class'] = !empty($animation)
                    ? ['uk-animation-{0}' => !in_array($animation, ['none', 'parallax']) ? $animation : false]
                    : true;
            } elseif ($svgAnimation) {
                $child->attrs['uk-scrollspy'] = ['target: [uk-scrollspy-class];'];
            }

        }
    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function parallax($node, array $params)
    {
        if (@$node->props['animation'] === 'parallax' || @$node->props['item_animation'] === 'parallax') {
            $node->attrs['class'][] = 'uk-position-z-index uk-position-relative {@parallax_zindex}';
            $node->attrs['uk-parallax'] = array_merge($params['view']->parallaxOptions($node->props), [
                'easing: {parallax_easing};',
                'target: !.uk-section; {@parallax_target}',
                'media: @{parallax_breakpoint};',
                'viewport: {parallax_viewport};',
            ]);
        }
    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function visibility($node, array $params)
    {
        $node->attrs['class'][] = 'uk-visible@{visibility}';

        /**
         * @var $parent
         */
        extract($params);

        if (empty($parent)) {
            return;
        }

        $visibilities = ['', 's', 'm', 'l', 'xl'];

        $parent->props['visibility'] = $visibilities[min(
            array_search(isset($node->props['visibility']) ? $node->props['visibility'] : '', $visibilities),
            array_search(isset($parent->props['visibility']) ? $parent->props['visibility'] : 'xl', $visibilities)
        )];

    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function margin($node, array $params)
    {
        if (@$node->type !== 'row') {
            $node->attrs['class'][] = 'uk-margin {@margin: default}';
            $node->attrs['class'][] = 'uk-margin-{!margin: |default}';
        }

        if (@$node->props['margin'] !== 'remove-vertical') {
            $node->attrs['class'][] = 'uk-margin-remove-top {@margin_remove_top}';
            $node->attrs['class'][] = 'uk-margin-remove-bottom {@margin_remove_bottom}';
        }
    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function maxWidth($node, array $params)
    {
        if (!empty($node->props['maxwidth'])) {
            $node->attrs['class'][] = 'uk-width-{maxwidth}[@{maxwidth_breakpoint}]';
            $node->attrs['class'][] = 'uk-margin-auto-left {@maxwidth_align: right}';
            $node->attrs['class'][] = 'uk-margin-auto {@maxwidth_align: center}';
        }
    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function textAlign($node, array $params)
    {
        if (!empty($node->props['text_align'])) {
            $node->attrs['class'][] = 'uk-text-{text_align}[@{text_align_breakpoint} [uk-text-{text_align_fallback}]]';
        }
    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function customCSS($node, array $params)
    {
        if (empty($node->props['css'])) {
            return;
        }

        if (empty($node->attrs['id'])) {
            $node->attrs['id'] = $node->id;
        }

        // Put all comma separations in one line to prevent faulty prefixing
        $css = self::prefixCSS("{$node->props['css']}\n", '#' . str_replace('#', '\#', $node->attrs['id']));

        /**
         * @var $path
         */
        extract($params);

        $layout = end($path);
        $layout->props['css'] = @$layout->props['css'] . $css;
    }

    /**
     * @param object $node
     * @param array  $params
     */
    public function containerPadding($node, array $params)
    {
        if (empty($node->props['container_padding_remove'])) {
            return;
        }

        /**
         * @var $builder
         * @var $path
         * @var $parent
         */
        extract($params);

        // Container Padding Remove
        $row = $builder->parent($path, 'row');
        $length = count($row->children);
        $index = (array_search($parent, $row->children) + (bool) $row->props['order_last']) % $length;

        $first = $index === 0;
        $last = $index + 1 === $length;

        foreach (['row', 'section'] as $type) {

            if (!in_array($builder->parent($path, $type, 'width'), ['default', 'small', 'large'])
                || !$dir = $builder->parent($path, $type, 'width_expand')
            ) {
                continue;
            }

            $node->attrs['class']['uk-container-item-padding-remove-left'] = $first && $dir === 'left';
            $node->attrs['class']['uk-container-item-padding-remove-right'] = $last && $dir === 'right';

            break;
        }
    }

    /**
     * Prefix CSS classes.
     *
     * @param string $css
     * @param string $prefix
     * @return string
     */
    protected static function prefixCSS($css, $prefix = '')
    {
        $pattern = '/([@#:\.\w\[\]][\\\\@#:,>~="\'\+\-\.\(\)\w\s\[\]\*]*)({(?:[^{}]+|(?R))*})/s';

        if (preg_match_all($pattern, $css, $matches, PREG_SET_ORDER)) {

            $keys = [];

            foreach ($matches as $match) {

                list($match, $selector, $content) = $match;

                if (in_array($key = sha1($match), $keys)) {
                    continue;
                }

                if ($selector[0] != '@') {
                    $selector = preg_replace('/(^|,)\s*/', "$0{$prefix} ", $selector);
                    $selector = preg_replace('/\s\.el-(element|section|column)/', '', $selector);
                }

                $css = str_replace($match, $selector . self::prefixCSS($content, $prefix), $css); $keys[] = $key;
            }
        }

        return $css;
    }
}
