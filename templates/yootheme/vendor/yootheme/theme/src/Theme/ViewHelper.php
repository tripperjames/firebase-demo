<?php

namespace YOOtheme\Theme;

use YOOtheme\ContainerTrait;
use YOOtheme\Util\Str;

// demo images

class ViewHelper
{
    use ContainerTrait;

    const REGEX_IMAGE = '#\.(gif|png|jpe?g|svg)$#';

    const REGEX_VIDEO = '#\.(mp4|m4v|ogv|webm)$#';

    const REGEX_VIMEO = '#(?:player\.)?vimeo\.com(?:/video)?/(\d+)#i';

    const REGEX_YOUTUBE = '#(?:youtube(-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})#i';

    const REGEX_UNSPLASH = '#images.unsplash.com/(?<id>(?:[\w-]+/)?[\w\-.]+)#i';

    /**
     * @var string
     */
    public $path;

    /**
     * @var object
     */
    public $theme;

    /**
     * @var array
     */
    public $inject = [
        'view' => 'app.view',
        'image' => 'app.image',
    ];

    /**
     * Constructor.
     *
     * @param string $path
     * @param object $theme
     */
    public function __construct($path, $theme)
    {
        $this->path = $path;
        $this->theme = $theme;
    }

    /**
     * Register helper.
     *
     * @param View $view
     */
    public function register($view)
    {
        // Functions
        $view->addFunction('social', [$this, 'social']);
        $view->addFunction('uid', [$this, 'uid']);
        $view->addFunction('iframeVideo', [$this, 'iframeVideo']);
        $view->addFunction('isVideo', [$this, 'isVideo']);
        $view->addFunction('isImage', [$this, 'isImage']);
        $view->addFunction('image', [$this, 'image']);
        $view->addFunction('bgImage', [$this, 'bgImage']);
        $view->addFunction('parallaxOptions', [$this, 'parallaxOptions']);

        // Components
        $view['html']->addComponent('image', [$this, 'comImage']);

        // demo videos

    }

    public function social($link)
    {
        if (Str::startsWith($link, 'mailto:')) {
            return 'mail';
        }

        if (Str::startsWith($link, 'tel:')) {
            return 'receiver';
        }

        if (preg_match('#google\.(.+?)/maps/(.+)#i', $link)) {
            return 'location';
        }

        $icon = parse_url($link, PHP_URL_HOST);
        $icon = preg_replace('/.*?(plus\.google|\w{3,}[^.]).*/i', '$1', $icon);
        $icon = str_replace('plus.google', 'google-plus', $icon);

        $icons = ['500px', 'behance', 'dribbble', 'facebook', 'github-alt', 'github', 'foursquare', 'tumblr', 'whatsapp', 'soundcloud', 'flickr', 'google-plus', 'google', 'linkedin', 'vimeo', 'instagram', 'joomla', 'pagekit', 'pinterest', 'reddit', 'tripadvisor', 'twitter', 'uikit', 'wordpress', 'xing', 'yelp', 'youtube'];

        if (!in_array($icon, $icons)) {
            $icon = 'social';
        }

        return $icon;
    }

    public function iframeVideo($link, $params = [], $defaults = true)
    {
        $query = parse_url($link, PHP_URL_QUERY);

        if ($query) {
            parse_str($query, $_params);
            $params = array_merge($_params, $params);
        }

        if (preg_match(self::REGEX_VIMEO, $link, $matches)) {
            return $this->app->url("https://player.vimeo.com/video/{$matches[1]}", $defaults ? array_merge([
                'loop' => 1,
                'autoplay' => 1,
                'title' => 0,
                'byline' => 0,
                'setVolume' => 0,
            ], $params) : $params);
        }

        if (preg_match(self::REGEX_YOUTUBE, $link, $matches)) {

            if (!empty($params['loop'])) {
                $params['playlist'] = $matches[2];
            }

            if (empty($params['controls'])) {
                $params['disablekb'] = 1;
            }

            return $this->app->url("https://www.youtube{$matches[1]}.com/embed/{$matches[2]}", $defaults ? array_merge([
                'rel' => 0,
                'loop' => 1,
                'playlist' => $matches[2],
                'autoplay' => 1,
                'controls' => 0,
                'showinfo' => 0,
                'iv_load_policy' => 3,
                'modestbranding' => 1,
                'wmode' => 'transparent',
                'playsinline' => 1,
            ], $params) : $params);
        }
    }

    public function uid() {
        return substr(uniqid(), -3);
    }

    public function isVideo($link)
    {
        return $link && preg_match(self::REGEX_VIDEO, $link, $matches) ? $matches[1] : false;
    }

    public function image($url, array $attrs = [])
    {
        $url = (array) $url;
        $path = array_shift($url);
        $isAbsolute = !$this->theme['customizer']->isActive() && preg_match('/^\/|#|[a-z0-9-.]+:/', $path);
        $type = $this->isImage($path);

        if (isset($url['thumbnail']) && count($url['thumbnail']) > 1 && preg_match(self::REGEX_UNSPLASH, $path, $matches)) {
            $path = "https://images.unsplash.com/{$matches['id']}?fit=crop&w={$url['thumbnail'][0]}&h={$url['thumbnail'][1]}";
        }

        // demo images image

        $params = $url && !$isAbsolute && $type != 'gif' ? '#'.http_build_query(array_map(function ($value) {
                return is_array($value) ? implode(',', $value) : $value;
            }, $url), '', '&') : '';

        $attrs['src'] = $path.$params;

        if (empty($attrs['alt'])) {
            $attrs['alt'] = true;
        }

        if (!empty($attrs['uk-img'])) {

            if (!$this->theme->get('lazyload')) {
                unset($attrs['uk-img']);
            } else {
                if ($type === 'svg' && !empty($attrs['uk-svg'])) {
                    $attrs['uk-img'] = $this->image->getUrl($attrs['src']);
                } else {
                    $attrs['data-src'] = $attrs['src'];
                }
                unset($attrs['src']);
            }

        }

        return "<img{$this->view->attrs($attrs)}>";
    }

    public function bgImage($url, array $params = [])
    {
        // demo images bgImage

        $attrs = [];
        $lazyload = $this->theme->get('lazyload');
        $isResized = $params['width'] || $params['height'];
        $type = $this->isImage($url);
        $isAbsolute = preg_match('/^\/|#|[a-z0-9-.]+:/', $url);

        if (preg_match(self::REGEX_UNSPLASH, $url, $matches)) {
            $url = "https://images.unsplash.com/{$matches['id']}?fit=crop&w={$params['width']}&h={$params['height']}";
        } elseif ($type == 'svg' || $isAbsolute) {
            if ($isResized && !$params['size']) {
                $width = $params['width'] ? "{$params['width']}px" : 'auto';
                $height = $params['height'] ? "{$params['height']}px" : 'auto';
                $attrs['style'][] = "background-size: {$width} {$height};";
            }
        } elseif ($type != 'gif') {
            $url .= '#srcset=1';
            $url .= '&covers=' . ((int) ($params['size'] === 'cover'));
            $url .= '&thumbnail' . ($isResized ? "={$params['width']},{$params['height']}" : '');
        }

        if ($lazyload) {

            if ($image = $this->image->create($url, false)) {
                $attrs = array_merge($attrs, $this->image->getSrcsetAttrs($image, 'data-'));
            } else {
                $attrs['data-src'][] = $this->image->getUrl($url);
            }

            $attrs['uk-img'] = true;

        } else {
            $attrs['style'][] = "background-image: url('{$this->image->getUrl($url)}');";
        }

        $attrs['class'] = [$this->view->cls([
            'uk-background-norepeat',
            'uk-background-{size}',
            'uk-background-{position}',
            'uk-background-image@{visibility}',
            'uk-background-blend-{blend_mode}',
            'uk-background-fixed{@effect: fixed}',
        ], $params)];

        $attrs['style'][] = $params['background'] ? "background-color: {$params['background']};" : '';

        switch ($params['effect']) {
            case '':
            case 'fixed':
                break;
            case 'parallax':

                $options = [];

                foreach(['bgx', 'bgy'] as $prop) {
                    $start = $params["parallax_{$prop}_start"];
                    $end = $params["parallax_{$prop}_end"];

                    if (strlen($start) || strlen($end)) {
                        $options[] = "{$prop}: ".(strlen($start) ? $start : 0).','.(strlen($end) ? $end : 0);
                    }
                }

                $options[] = $params['parallax_breakpoint'] ? "media: @{$params['parallax_breakpoint']}" : '';
                $options[] = @$params['parallax_target'] ? "target: {$params['parallax_target']}" : '';
                $attrs['uk-parallax'] = implode(';', array_filter($options));

                break;
        }

        return $attrs;
    }

    public function comImage($element, array $params = [])
    {
        $defaults = ['src' => '', 'width' => '', 'height' => ''];
        $attrs = array_merge($defaults, $element->attrs);
        $type = $this->isImage($attrs['src']);

        if (empty($attrs['alt'])) {
            $attrs['alt'] = true;
        }

        // demo images comImage

        if ($type !== 'svg') {

            $query = [];

            if (!empty($attrs['thumbnail'])) {

                $query['thumbnail'] = is_array($attrs['thumbnail']) ? $attrs['thumbnail'] : [$attrs['width'], $attrs['height']];
                $query['srcset'] = true;

                // use unsplash resizing?
                if (preg_match(self::REGEX_UNSPLASH, $attrs['src'], $matches) && count($query['thumbnail']) > 1) {
                    $attrs['src'] = "https://images.unsplash.com/{$matches['id']}?fit=crop&w={$query['thumbnail'][0]}&h={$query['thumbnail'][1]}";
                }
            }

            if (!empty($attrs['uk-cover'])) {
                $query['covers'] = true;
            }

            if ($type === 'gif') {
                $attrs['uk-gif'] = true;
            } elseif ($this->theme['customizer']->isActive() || !$this->isAbsolute($attrs['src'])) {
                $attrs['src'] .= $query ? '#'.http_build_query(array_map(function ($value) {
                    return is_array($value) ? join(',', $value) : $value;
                }, $query), '', '&') : '';
            }

            unset($attrs['width'], $attrs['height'], $attrs['uk-svg']);
        }

        // use lazy loading?
        if ($this->theme->get('lazyload')) {

            if ($type === 'svg' && !empty($attrs['uk-svg'])) {
                $attrs['uk-img'] = $this->image->getUrl($attrs['src']);
            } else {
                $attrs['data-src'] = $attrs['src'];
            }

            if (empty($attrs['uk-img'])) {
                $attrs['uk-img'] = true;
            }

            unset($attrs['src']);
        } else {
            unset($attrs['uk-img']);
        }

        unset($attrs['thumbnail']);

        // update element
        $element->name = 'img';
        $element->attrs = $attrs;
    }

    public function isImage($link)
    {
        return $link && preg_match(self::REGEX_IMAGE, $link, $matches) ? $matches[1] : false;
    }

    public function isAbsolute($url)
    {
        return $url && preg_match('/^\/|#|[a-z0-9-.]+:/', $url);
    }

    public function parallaxOptions($params, $prefix = '') {
        return array_reduce(['x', 'y', 'scale', 'rotate', 'opacity'], function ($options, $prop) use ($params, $prefix) {
            $start = @$params["{$prefix}parallax_{$prop}_start"];
            $end = @$params["{$prefix}parallax_{$prop}_end"];
            $default = in_array($prop, ['scale', 'opacity']) ? 1 : 0;

            if (strlen($start) || strlen($end)) {
                $start = strlen($start) ? $start : $default;
                $middle = $prefix ? "{$default}," : '';
                $end = strlen($end) ? $end : $default;

                $options[] = "{$prop}: {$start},{$middle}{$end};";
            }

            return $options;
        }, []);
    }
}
