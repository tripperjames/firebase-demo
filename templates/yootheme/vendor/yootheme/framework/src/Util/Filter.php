<?php

namespace YOOtheme\Util;

class Filter
{
    /**
     * @var FilterManager
     */
    protected static $manager;

    /**
     * Gets the filter manager.
     *
     * @return FilterManager
     */
    public static function getManager()
    {
        return static::$manager ?: (static::$manager = new FilterManager);
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
        $manager = static::getManager();

        if (!is_callable([$manager, $name])) {
            trigger_error(sprintf('Call to undefined method %s::%s()', get_called_class(), $name), E_USER_ERROR);
        }

        return call_user_func_array([$manager, $name], $args);
    }
}
