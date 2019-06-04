<?php

namespace YOOtheme;

class Event implements EventInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed
     */
    public $result;

    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation()
    {
        $this->stopped = true;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped()
    {
        return $this->stopped;
    }
}
