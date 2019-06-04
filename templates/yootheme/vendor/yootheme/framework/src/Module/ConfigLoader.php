<?php

namespace YOOtheme\Module;

use YOOtheme\Util\Collection;
use YOOtheme\Util\Filter;
use YOOtheme\Util\FilterManager;

class ConfigLoader
{
    /**
     * @var array
     */
    protected $values;

    /**
     * @var FilterManager
     */
    protected $filter;

    /**
     * Constructor.
     *
     * @param array         $values
     * @param FilterManager $filter
     */
    public function __construct(array $values = [], FilterManager $filter = null)
    {
        $this->values = $values;
        $this->filter = $filter ?: Filter::getManager();
    }

    /**
     * Loader callback function.
     *
     * @param  array    $options
     * @param  callable $next
     * @return mixed
     */
    public function __invoke($options, $next)
    {
        if (isset($this->values[$options['name']])) {
            $options = array_replace_recursive($options,
                ['config' => $this->values[$options['name']]]
            );
        }

        if (isset($options['replacements'])) {
            $this->filter->addReplacements($options['replacements']);
        }

        $module = $next($options);

        if (!isset($module['config'])) {
            $module['config'] = function () use ($module) {

                array_walk_recursive($module->options['config'], function (&$value) {
                    $value = $this->filter->replace($value);
                });

                return new Collection($module->options['config']);
            };
        }

        return $module;
    }
}
