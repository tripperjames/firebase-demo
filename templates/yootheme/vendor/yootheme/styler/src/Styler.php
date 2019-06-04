<?php

namespace YOOtheme\Theme;

use YOOtheme\EventSubscriberInterface;
use YOOtheme\Module;
use YOOtheme\Util\Collection;

class Styler extends Module implements EventSubscriberInterface
{
    /**
     * @var array
     */
    public $inject = [

        'view' => 'app.view',
        'styles' => 'app.styles',
        'scripts' => 'app.scripts',
        'locator' => 'app.locator',

    ];

    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        $this['data'] = function () {
            return new Collection();
        };

        $this['themes'] = function () {

            $themes = [];

            foreach ($this->locator->findAll('@theme/less/theme.*.less') as $file) {
                $id = substr(basename($file, '.less'), 6);
                $themes[$id] = array_merge([
                    'id' => $id,
                    'file' => $file,
                    'name' => $this->namify($id),
                ], $this->getMeta($file));
            }

            return $themes;

        };
    }

    public function onInit($theme)
    {
        // set defaults
        $theme->merge($this->config['defaults'], true);
    }

    public function onSite($theme)
    {
        // set fonts, deprecated in v1.5
        if ($fonts = $theme->config->get('fonts', [])) {
            $this->styles->add('google-fonts', $this->app->url('https://fonts.googleapis.com/css', [
                'family' => implode('|', array_map(function ($font) {
                    return trim($font['name'], "'").($font['variants'] ? ':'.$font['variants'] : '');
                }, $fonts)),
                'subset' => rtrim(implode(',', array_unique(array_map('trim', explode(',', implode(',', array_map(function ($font) {
                    return $font['subsets'];
                }, $fonts)))))), ',') ?: null,
            ]));
        }
    }

    public function onAdmin($theme)
    {
        // check if theme css needs to be updated
        $style = $this->locator->find("@theme/css/theme.{$theme->id}.css");
        $update = !$style || filemtime(__FILE__) >= filemtime($style);

        $this->config->merge([
            'section' => [
                'route' => $this->app->route('theme/styles'),
                'worker' => $this->app->url("{$this->path}/app/worker.min.js", ['ver' => $theme->options['version']]),
                'styles' => array_map(function ($theme, $id) {
                    $theme['id'] = $id;
                    unset($theme['file']);
                    return $theme;
                }, $this['themes'], array_keys($this['themes'])),
                'update' => $update,
            ],
        ], true);

        $this->scripts->add('customizer-styler', "{$this->path}/app/styler.min.js", 'customizer');
    }

    protected function getMeta($file)
    {
        $meta = [];
        $style = false;
        $handle = fopen($file, 'r');
        $content = str_replace("\r", "\n", fread($handle, 8192));
        fclose($handle);

        // parse first comment
        if (!preg_match('/^\s*\/\*(?:(?!\*\/).|\n)+\*\//', $content, $matches)) {
            return $meta;
        }

        // parse all metadata
        if (!preg_match_all('/^[ \t\/*#@]*(name|style|background|color|type):(.*)$/mi', $matches[0], $matches)) {
            return $meta;
        }

        foreach ($matches[1] as $i => $key) {

            $key = strtolower(trim($key));
            $value = trim($matches[2][$i]);

            if (!in_array($key, ['name', 'style'])) {
                $value = array_map('ucwords', array_map('trim', explode(',', $value)));
            }

            if (!$style && $key != 'style') {
                $meta[$key] = $value;
            } elseif ($key == 'style') {
                $style = $value;
                $meta['styles'][$style] = ['name' => $this->namify($style)];
            } else {
                $meta['styles'][$style][$key] = $value;
            }
        }

        return $meta;
    }

    protected function namify($id)
    {
        return ucwords(str_replace('-', ' ', $id));
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => 'onInit',
            'theme.site' => ['onSite', -5],
            'theme.admin' => 'onAdmin',
        ];
    }
}
