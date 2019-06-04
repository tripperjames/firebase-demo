<?php

namespace YOOtheme\Module;

use YOOtheme\EventManagerInterface;
use YOOtheme\EventSubscriberInterface;

class EventLoader
{
    /**
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Constructor.
     *
     * @param EventManagerInterface $events
     */
    public function __construct(EventManagerInterface $events)
    {
        $this->events = $events;
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
        $module = $next($options);

        if (isset($module->options['events'])) {
            foreach ($module->options['events'] as $event => $listener) {

                $priority = 0;

                if (!is_callable($listeners = (array) $listener)) {

                    for ($i = 0, $j = 1; $i < count($listeners); $i++, $j++) {

                        $listener = $listeners[$i];

                        if ($listener instanceof \Closure) {
                            $listener = $listener->bindTo($module, $module);
                        }

                        if (isset($listeners[$j]) && is_numeric($listeners[$j])) {
                            $priority = $listeners[$j]; $i++;
                        }

                        $this->events->on($event, $listener, $priority);
                    }

                } else {

                    $this->events->on($event, $listener, $priority);
                }
            }
        }

        if ($module instanceof EventSubscriberInterface) {
            $this->events->subscribe($module);
        }

        return $module;
    }
}
