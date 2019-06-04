<?php

namespace YOOtheme;

use YOOtheme\Theme\ViewHelper;
use YOOtheme\Util\Collection;
use YOOtheme\Util\MethodTrait;

class Theme extends Module
{
    use MethodTrait;

    const THEME_FILE_GLOB_PATTERN = 'theme*.css';

    public $data;

    /**
     * {@inheritdoc}
     */
    public function __invoke($app)
    {
        $this->data = new Collection();

        $this->view
            ->addGlobal('theme', $this)
            ->addLoader([$this, 'replace'])
            ->addHelper([new ViewHelper(dirname(__DIR__), $this), 'register']);

        $this->locator
            ->addPath($this->path, 'theme')
            ->addPath($this->path, 'assets')
            ->addPath("{$this->path}/templates", 'views');

        $this['debug'] = function () {
            return isset($this->options['debug']) && $this->options['debug'];
        };

        $app->provide('theme', $this);
    }

    /**
     * Prevent fatal error on serialize.
     *
     * @return array
     */
    public function __sleep()
    {
        return [];
    }

    /**
     * Gets a config value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $this->config->get($key, $default);
        return is_array($value) ? new Collection($value) : $value;
    }

    /**
     * Sets a config value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function set($key, $value)
    {
        $this->config->set($key, $value);
        return $this;
    }

    /**
     * Checks if a config value exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->config->has($key);
    }

    /**
     * Merges an array of config values.
     *
     * @param mixed $items
     * @param bool  $recursive
     *
     * @return self
     */
    public function merge($items, $recursive = false)
    {
        $this->config->merge($items, $recursive);

        return $this;
    }

    /**
     * Renders a template.
     *
     * @param string $name
     * @param mixed  $parameters
     *
     * @return string|false
     */
    public function render($name, $parameters = [])
    {
        return $this->view->render($name, $parameters);
    }

    /**
     * Replaces images URLs.
     *
     * @param string   $name
     * @param mixed    $parameters
     * @param callable $next
     *
     * @return string
     */
    public function replace($name, $parameters, $next)
    {
        return $this->image->replace($next($name, $parameters));
    }
}
