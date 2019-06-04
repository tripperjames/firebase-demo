<?php

namespace YOOtheme\Util;

trait MethodTrait
{
    /**
     * @var array
     */
    protected static $methods = [];

    /**
     * Checks if a method exists.
     *
     * @param  string $name
     * @return bool
     */
    public static function hasMethod($name)
    {
        $name = strtolower($name);

        return isset(static::$methods[$name]);
    }

    /**
     * Defines a custom method.
     *
     * @param  string   $name
     * @param  callable $callback
     */
    public static function defineMethod($name, callable $callback)
    {
        $name = strtolower($name);

        static::$methods[$name] = $callback;
    }

    /**
     * Handles dynamic calls to the class.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (!static::hasMethod($name)) {
            trigger_error(sprintf('Call to undefined method %s::%s()', get_class($this), $name), E_USER_ERROR);
        }

        $name = strtolower($name);

        if (static::$methods[$name] instanceof \Closure) {
            return call_user_func_array(static::$methods[$name]->bindTo($this, $this), $args);
        }

        return call_user_func_array(static::$methods[$name], $args);
    }

    /**
     * Handles dynamic calls to the class.
     *
     * @param  string $name
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        if (!static::hasMethod($name)) {
            trigger_error(sprintf('Call to undefined method %s::%s()', get_called_class(), $name), E_USER_ERROR);
        }

        $name = strtolower($name);

        if (static::$methods[$name] instanceof \Closure) {
            return call_user_func_array(\Closure::bind(static::$methods[$name], null, get_called_class()), $args);
        }

        return call_user_func_array(static::$methods[$name], $args);
    }
}
