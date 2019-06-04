<?php

namespace YOOtheme;

class Container implements \ArrayAccess
{
    /**
     * @var array
     */
    public static $providers = [];

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $raw = [];

    /**
     * @var \SplObjectStorage
     */
    protected $factories;

    /**
     * @var \SplObjectStorage
     */
    protected $protected;

    /**
     * Constructor.
     *
     * @param array  $values
     * @param string $name
     */
    public function __construct(array $values = [], $name = null)
    {
        $this->factories = new \SplObjectStorage();
        $this->protected = new \SplObjectStorage();

        if ($name) {
            $this->provide($name, $this);
        }

        foreach ($values as $name => $value) {
            $this->offsetSet($name, $value);
        }
    }

    /**
     * Adds a provider object.
     *
     * @param string $name
     * @param mixed  $provider
     */
    public function provide($name, $provider)
    {
        self::$providers[$name] = $provider;
    }

    /**
     * Marks a closure as factory.
     *
     * @param \Closure $closure
     */
    public function factory(\Closure $closure)
    {
        $this->factories->attach($closure);

        return $closure;
    }

    /**
     * Marks a closure as protected.
     *
     * @param \Closure $closure
     */
    public function protect(\Closure $closure)
    {
        $this->protected->attach($closure);

        return $closure;
    }

    /**
     * Extends an existing object definition.
     *
     * @param  string   $name
     * @param  \Closure $closure
     * @throws \InvalidArgumentException
     */
    public function extend($name, \Closure $closure)
    {
        if (!array_key_exists($name, $this->values)) {
            throw new \InvalidArgumentException("\"{$name}\" is not defined");
        }

        $factory = $this->values[$name];

        if (!$factory instanceof \Closure) {

            if ($closure($factory, $this) ?: $factory !== $factory) {
                throw new \InvalidArgumentException("Extending the resolved service \"{$name}\" must return the same instance");
            }

            return $factory;
        }

        $extended = function ($container) use ($closure, $factory) {
            return $closure($value = $factory($container), $container) ?: $value;
        };

        if ($this->factories->contains($factory)) {
            $this->factories->detach($factory);
            $this->factories->attach($extended);
        }

        return $this[$name] = $extended;
    }

    /**
     * Gets a parameter or object without resolving.
     *
     * @param  string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function raw($name)
    {
        if (!array_key_exists($name, $this->values)) {
            throw new \InvalidArgumentException("\"{$name}\" is not defined");
        }

        return isset($this->raw[$name]) ? $this->raw[$name] : $this->values[$name];
    }

    /**
     * Returns all defined names.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->values);
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param  string $name
     * @return bool
     */
    public function offsetExists($name)
    {
        return array_key_exists($name, $this->values);
    }

    /**
     * Gets a parameter or an object.
     *
     * @param  string $name
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function offsetGet($name)
    {
        if (!array_key_exists($name, $this->values)) {
            throw new \InvalidArgumentException("\"{$name}\" is not defined");
        }

        if (isset($this->raw[$name]) || !is_object($this->values[$name]) || $this->protected->contains($this->values[$name]) || !($this->values[$name] instanceof \Closure)) {
            return $this->values[$name];
        }

        if ($this->factories->contains($this->values[$name])) {
            return $this->values[$name]($this);
        }

        $this->raw[$name] = $this->values[$name];

        return $this->values[$name] = $this->values[$name]($this);
    }

    /**
     * Sets a parameter or an object.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @throws \RuntimeException
     */
    public function offsetSet($name, $value)
    {
        if (isset($this->raw[$name])) {
            throw new \RuntimeException("Cannot override service definition \"{$name}\"");
        }

        $this->values[$name] = $value;
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $name
     */
    public function offsetUnset($name)
    {
        if (array_key_exists($name, $this->values)) {

            if (is_object($this->values[$name])) {
                $this->factories->detach($this->values[$name]);
                $this->protected->detach($this->values[$name]);
            }

            unset($this->values[$name], $this->raw[$name]);
        }
    }
}
