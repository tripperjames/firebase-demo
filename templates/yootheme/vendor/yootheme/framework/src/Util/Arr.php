<?php

namespace YOOtheme\Util;

class Arr
{
    use MethodTrait;

    /**
     * Checks if the given key exists.
     *
     * @param  array|\ArrayAccess $array
     * @param  string             $key
     * @return bool
     */
    public static function has($array, $key)
    {
        if ($key === null) {
            return false;
        }

        if (static::exists($array, $key)) {
            return true;
        }

        if (!strpos($key, '.')) {
            return false;
        }

        foreach (explode('.', $key) as $part) {
            if (static::exists($array, $part)) {
                $array = $array[$part];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets a value by key.
     *
     * @param  array|\ArrayAccess $array
     * @param  string             $key
     * @param  mixed              $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if ($key === null) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        if (!strpos($key, '.')) {
            return $default;
        }

        foreach (explode('.', $key) as $part) {
            if (static::exists($array, $part)) {
                $array = $array[$part];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Sets a value.
     *
     * @param  array  $array
     * @param  string $key
     * @param  mixed  $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if ($key === null) {
            return $array = $value;
        }

        $parts = explode('.', $key);

        while (count($parts) > 1) {

            $part = array_shift($parts);

            if (!isset($array[$part]) || !is_array($array[$part])) {
                $array[$part] = [];
            }

            $array = &$array[$part];
        }

        $array[array_shift($parts)] = $value;

        return $array;
    }

    /**
     * Removes a value from array.
     *
     * @param  array $array
     * @param  mixed $value
     * @param  bool  $strict
     * @return array
     */
    public static function pull(&$array, $value, $strict = false)
    {
        if ($keys = array_keys($array, $value, $strict)) {

            foreach ($keys as $key) {
                unset($array[$key]);
            }

            $new = array_values($array);
            $array = &$new;
        }

        return $array;
    }

    /**
     * Removes a value from array by key.
     *
     * @param array        $array
     * @param array|string $keys
     */
    public static function remove(&$array, $keys)
    {
        $original = &$array;

        foreach ((array) $keys as $key) {

            $array = &$original;
            $parts = explode('.', $key);

            while (count($parts) > 1) {

                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Checks if all values pass the predicate truth test.
     *
     * @param  array          $array
     * @param  array|callable $predicate
     * @return bool
     */
    public static function every($array, $predicate)
    {
        $callback = is_callable($predicate) ? $predicate : static::matches($predicate);

        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if some values pass the predicate truth test.
     *
     * @param  array          $array
     * @param  array|callable $predicate
     * @return bool
     */
    public static function some($array, $predicate)
    {
        $callback = is_callable($predicate) ? $predicate : static::matches($predicate);

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the picked values from the given array.
     *
     * @param  array        $array
     * @param  array|string $keys
     * @return array
     */
    public static function pick($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    /**
     * Gets all of the given array without the specified keys.
     *
     * @param  array        $array
     * @param  array|string $keys
     * @return array
     */
    public static function omit($array, $keys)
    {
        static::remove($array, $keys);

        return $array;
    }

    /**
     * Gets the first value in an array passing the predicate truth test.
     *
     * @param  array          $array
     * @param  array|callable $predicate
     * @return mixed
     */
    public static function find($array, $predicate)
    {
        $callback = is_callable($predicate) ? $predicate : static::matches($predicate);

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Gets all values in an array passing the predicate truth test.
     *
     * @param  array          $array
     * @param  array|callable $predicate
     * @return array
     */
    public static function filter($array, $predicate = null)
    {
        $callback = is_null($predicate) || is_callable($predicate) ? $predicate : static::matches($predicate);

        if (isset($callback) && version_compare(PHP_VERSION, '5.6.0', '<')) {

            $filtered = [];

            foreach ($array as $key => $value) {
                if ($callback($value, $key)) {
                    $filtered[$key] = $value;
                }
            }

            return $filtered;
        }

        return $callback ? array_filter($array, $callback, 1) : array_filter($array);
    }

    /**
     * Merges two arrays.
     *
     * @param  array $array1
     * @param  array $array2
     * @param  bool  $recursive
     * @return array
     */
    public static function merge($array1, $array2, $recursive = false)
    {
        if (!$recursive) {
            return array_merge($array1, $array2);
        }

        foreach ($array2 as $key => $value) {
            if (isset($array1[$key])) {

                if (is_int($key)) {
                    $array1[] = $value;
                } else if (is_array($value) && is_array($array1[$key])) {
                    $array1[$key] = static::merge($array1[$key], $value, $recursive);
                } else {
                    $array1[$key] = $value;
                }

            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }

    /**
     * Extracts values by keys.
     *
     * @param  array $data
     * @param  array $keys
     * @param  bool  $include
     * @return array
     */
    public static function extract($data, array $keys = null, $include = true)
    {
        if (!$keys) {
            return $data;
        }

        $data = static::flatten($data);
        $result = [];

        foreach ($data as $keypath => $value) {

            $add = !$include;

            foreach ($keys as $key) {
                if (0 === strpos($keypath, $key)) {
                    $add = $include;
                    break;
                }
            }

            if ($add) {
                $result[$keypath] = $value;
            }
        }

        return static::expand($result);
    }

    /**
     * Flattens an array.
     *
     * @param  array $array
     * @param  string $path
     * @return array
     */
    public static function flatten($array, $path = '')
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, static::flatten($value, $path.$key.'.'));
            } else {
                $result[$path.$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Expands an array.
     *
     * @param  array $array
     * @return array
     */
    public static function expand($array)
    {
        $result = [];

        foreach ($array as $key => $value) {

            $keys = explode('.', $key);
            $values = &$result;

            while (count($keys) > 1) {

                $key = array_shift($keys);

                if (!isset($values[$key]) || !is_array($values[$key])) {
                    $values[$key] = [];
                }

                $values = &$values[$key];
            }

            $values[array_shift($keys)] = $value;
        }

        return $result;
    }

    /**
     * Checks if the given key exists.
     *
     * @param  array|\ArrayAccess $array
     * @param  string             $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if (is_array($array)) {
            return array_key_exists($key, $array);
        }

        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return false;
    }

    /**
     * Checks if the given value is array accessible.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * Creates a callback function to match array values.
     *
     * @param  array    $predicate
     * @return callable
     */
    protected static function matches(array $predicate)
    {
        return function ($array) use ($predicate) {

            if (!static::accessible($array)) {
                return false;
            }

            foreach ($predicate as $key => $value) {

                if (!static::exists($array, $key)) {
                    return false;
                }

                if ($array[$key] != $value) {
                    return false;
                }
            }

            return true;
        };
    }
}
