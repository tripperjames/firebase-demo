<?php

namespace YOOtheme\Util;

class FilterManager
{
    /**
     * @var array
     */
    protected $sorted = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $replacements = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->add('all', [$this, 'replaceFilter'], 10);
    }

    /**
     * Adds a filter callback.
     *
     * @param  string   $name
     * @param  callable $filter
     * @param  int      $priority
     * @return self
     */
    public function add($name, $filter, $priority = 0)
    {
        $this->filters[$name][$priority][] = $filter;

        if ($name == 'all') {
            $this->sorted = [];
        } else {
            unset($this->sorted[$name]);
        }

        return $this;
    }

    /**
     * Removes one or more filters.
     *
     * @param  string   $name
     * @param  callable $filter
     * @return self
     */
    public function remove($name, $filter = null)
    {
        if (!isset($this->filters[$name])) {
            return;
        }

        if ($filter === null) {
            unset($this->filters[$name], $this->sorted[$name]);
            return;
        }

        foreach ($this->filters[$name] as $priority => $filters) {
            if (false !== ($key = array_search($filter, $filters, true))) {
                unset($this->filters[$name][$priority][$key], $this->sorted[$name]);
            }
        }

        return $this;
    }

    /**
     * Applies all filters on a value.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return mixed
     */
    public function apply($name, $value)
    {
        $num = func_num_args() - 1;
        $args = array_slice(func_get_args(), 1);

        foreach ($this->getFilters($name) as $filter) {
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
    public function getFilters($name = null)
    {
        if ($name !== null) {
            return isset($this->sorted[$name]) ? $this->sorted[$name] : $this->sortFilters($name);
        }

        foreach (array_keys($this->filters) as $name) {
            if (!isset($this->sorted[$name])) {
                $this->sortFilters($name);
            }
        }

        return array_filter($this->sorted);
    }

    /**
     * Gets the replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return $this->replacements;
    }

    /**
     * Adds replacements.
     *
     * @param  array $replacements
     * @return self
     */
    public function addReplacements(array $replacements)
    {
        foreach ($replacements as $name => $value) {
            $this->replacements['{'.$name.'}'] = $value;
        }

        return $this;
    }

    /**
     * Replace all given replacements on a value.
     *
     * @param  mixed $value
     * @param  array $replacements
     * @return mixed
     */
    public function replace($value, array $replacements = [])
    {
        if (is_string($value) && strpos($value, '{') !== false) {

            $replace = $this->replacements;

            foreach ($replacements as $name => $val) {
                $replace['{'.$name.'}'] = $val;
            }

            if (isset($replace[$value])) {

                if (is_array($replace[$value])) {
                    array_walk_recursive($replace[$value], function (&$value) use ($replacements) {
                        $value = $this->replace($value, $replacements);
                    });
                }

                return $replace[$value];
            }

            return strtr($value, array_filter($replace, 'is_string'));
        }

        return $value;
    }

    /**
     * Replace filter callback.
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function replaceFilter($value)
    {
        if (is_string($value) && strpos($value, '{') !== false) {

            if (isset($this->replacements[$value])) {
                return $this->replacements[$value];
            }

            return strtr($value, array_filter($this->replacements, 'is_string'));
        }

        return $value;
    }

    /**
     * Sorts all filters by their priority.
     *
     * @param  string $name
     * @return array
     */
    protected function sortFilters($name)
    {
        $sorted = [];

        if (isset($this->filters['all'])) {
            $sorted = $this->filters['all'];
        }

        if (isset($this->filters[$name])) {

            foreach (array_intersect_key($this->filters[$name], $sorted) as $priority => $filters) {
                $sorted[$priority] = array_merge($sorted[$priority], $filters);
            }

            foreach (array_diff_key($this->filters[$name], $sorted) as $priority => $filters) {
                $sorted[$priority] = $filters;
            }
        }

        krsort($sorted);

        return $this->sorted[$name] = $sorted ? call_user_func_array('array_merge', $sorted) : $sorted;
    }
}
