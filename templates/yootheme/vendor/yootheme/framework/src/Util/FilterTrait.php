<?php

namespace YOOtheme\Util;

trait FilterTrait
{
    /**
     * @var array
     */
    protected static $sorted = [];

    /**
     * @var array
     */
    protected static $filters = [];

    /**
     * Adds a filter callback.
     *
     * @param string   $name
     * @param callable $filter
     * @param int      $priority
     */
    public static function addFilter($name, $filter, $priority = 0)
    {
        static::$filters[$name][$priority][] = $filter;

        if ($name == 'all') {
            static::$sorted = [];
        } else {
            unset(static::$sorted[$name]);
        }
    }

    /**
     * Removes one or more filters.
     *
     * @param string   $name
     * @param callable $filter
     */
    public static function removeFilter($name, $filter = null)
    {
        if (!isset(static::$filters[$name])) {
            return;
        }

        if ($filter === null) {
            unset(static::$filters[$name], static::$sorted[$name]);
            return;
        }

        foreach (static::$filters[$name] as $priority => $filters) {
            if (false !== ($key = array_search($filter, $filters, true))) {
                unset(static::$filters[$name][$priority][$key], static::$sorted[$name]);
            }
        }
    }

    /**
     * Applies all filters on a value.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return mixed
     */
    public static function applyFilter($name, $value)
    {
        $num = func_num_args() - 1;
        $args = array_slice(func_get_args(), 1);

        foreach (static::getFilters($name) as $filter) {
            if ($num == 1) {
                $args[0] = $filter($args[0]);
            } elseif ($num == 2) {
                $args[0] = $filter($args[0], $args[1]);
            } elseif ($num == 3) {
                $args[0] = $filter($args[0], $args[1], $args[2]);
            } else {
                $args[0] = call_user_func_array($filter, $args);
            }
        }

        return $args[0];
    }

    /**
     * Gets all filters.
     *
     * @param  string $name
     * @return array
     */
    public static function getFilters($name = null)
    {
        if ($name !== null) {
            return isset(static::$sorted[$name]) ? static::$sorted[$name] : static::sortFilters($name);
        }

        foreach (array_keys(static::$filters) as $name) {
            if (!isset(static::$sorted[$name])) {
                static::sortFilters($name);
            }
        }

        return array_filter(static::$sorted);
    }

    /**
     * Sorts all filters by their priority.
     *
     * @param  string $name
     * @return array
     */
    protected static function sortFilters($name)
    {
        $sorted = [];

        if (isset(static::$filters['all'])) {
            $sorted = static::$filters['all'];
        }

        if (isset(static::$filters[$name])) {

            foreach (array_intersect_key(static::$filters[$name], $sorted) as $priority => $filters) {
                $sorted[$priority] = array_merge($sorted[$priority], $filters);
            }

            foreach (array_diff_key(static::$filters[$name], $sorted) as $priority => $filters) {
                $sorted[$priority] = $filters;
            }
        }

        krsort($sorted);

        return static::$sorted[$name] = $sorted ? call_user_func_array('array_merge', $sorted) : $sorted;
    }
}
