<?php

namespace YOOtheme;

class Route
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string|callable
     */
    protected $callable;

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Constructor.
     *
     * @param string          $path
     * @param string|callable $callable
     * @param string|string[] $methods
     */
    public function __construct($path, $callable, $methods = [])
    {
        $this->setPath($path);
        $this->setMethods($methods);
        $this->callable = $callable;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = trim($name);

        return $this;
    }

    /**
     * Gets the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the path.
     *
     * @param  string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = '/'.trim($path, '/');

        return $this;
    }

    /**
     * Gets the callable.
     *
     * @return string|callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * Gets the methods.
     *
     * @return string[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Sets the methods.
     *
     * @param  string|string[] $methods
     * @return self
     */
    public function setMethods($methods)
    {
        $this->methods = array_map('strtoupper', (array) $methods);

        return $this;
    }

    /**
     * Gets an attribute.
     *
     * @param  string $name
     * @param  mixed  $default
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Sets an attribute.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return self
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Gets the attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the attributes.
     *
     * @param  array $attributes
     * @return self
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }
}
