<?php

namespace YOOtheme\Theme;

class JoomlaViews extends \ArrayObject
{
    protected $theme;

    protected $childTheme;

    public function __construct($theme, $childTheme)
    {
        $this->theme = $theme;
        $this->childTheme = $childTheme;
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            $this->offsetSet($offset, new \ArrayObject());
        }

        $name = parent::offsetGet($offset);

        if (isset($name['html'])) {

            foreach ($name['html'] as $view) {

                $paths = $view->get('_path');

                if ($path = isset($paths['template'][0]) ? $paths['template'][0] : false) {

                    if (strpos($path, $this->theme.DIRECTORY_SEPARATOR) !== false) {
                        array_unshift($paths['template'], preg_replace("/($this->theme(?!.*$this->theme.*))/", "$this->childTheme", $path));
                    }

                }

                $view->set('_path', $paths);
            }

        }

        return $name;
    }
}
