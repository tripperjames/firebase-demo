<?php

namespace YOOtheme;

class ModuleKernel
{
    /**
     * @var array
     */
    protected $defaults = [
        'main' => null,
        'type' => 'module',
        'class' => 'YOOtheme\Module',
        'config' => []
    ];

    /**
     * Loader callback.
     *
     * @param  array $options
     * @return mixed
     */
    public function __invoke(array $options)
    {
        $options = array_replace($this->defaults, $options);
        $class = $options[is_string($options['main']) ? 'main' : 'class'];

        return new $class($options);
    }
}
