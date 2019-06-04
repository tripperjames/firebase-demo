<?php

namespace YOOtheme;

class Module extends Container
{
    use ContainerTrait;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $options;

    /**
     * @var array
     */
    public $inject;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->name = $options['name'];
        $this->path = $options['path'];
        $this->options = $options;

        if (isset($options['inject'])) {
            $this->inject = array_replace((array) $this->inject, $options['inject']);
        }

        parent::__construct([], $this->name);
    }

    /**
     * Bootstrap callback.
     *
     * @param $app Application
     */
    public function __invoke($app)
    {
        $main = $this->options['main'];

        if ($main instanceof \Closure) {
            $main = $main->bindTo($this, $this);
        }

        if (is_callable($main)) {
            return $main($app, $this);
        }
    }
}
