<?php

namespace YOOtheme;

class EventManager implements EventManagerInterface
{
    /**
     * @var string
     */
    protected $event;

    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @var array
     */
    protected $sorted = [];

    /**
     * Constructor.
     *
     * @param string $event
     */
    public function __construct($event = 'YOOtheme\Event')
    {
        $this->event = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function on($event, $listener, $priority = 0)
    {
        $this->listeners[$event][$priority][] = $listener;
        unset($this->sorted[$event]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function off($event, $listener = null)
    {
        if (!isset($this->listeners[$event])) {
            return;
        }

        if ($listener === null) {
            unset($this->listeners[$event], $this->sorted[$event]);
            return;
        }

        foreach ($this->listeners[$event] as $priority => $listeners) {
            if (false !== ($key = array_search($listener, $listeners, true))) {
                unset($this->listeners[$event][$priority][$key], $this->sorted[$event]);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $event => $params) {
            if (is_string($params)) {
                $this->on($event, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $this->on($event, [$subscriber, $params[0]], isset($params[1]) ? $params[1] : 0);
            } else {
                foreach ($params as $listener) {
                    $this->on($event, [$subscriber, $listener[0]], isset($listener[1]) ? $listener[1] : 0);
                }
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribe(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $event => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->off($event, [$subscriber, $listener[0]]);
                }
            } else {
                $this->off($event, [$subscriber, is_string($params) ? $params : $params[0]]);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function trigger($event, array $parameters = [])
    {
        if (is_string($event)) {
            $event = new $this->event($event);
        } elseif (is_a($event, $this->event)) {
            array_unshift($parameters, $event);
        } else {
            throw new \RuntimeException(sprintf('Event must be an instance of "%s"', $this->event));
        }

        foreach ($this->getListeners($event->getName()) as $listener) {

            $result = call_user_func_array($listener, $parameters);

            if ($result !== null) {

                $event->result = $result;

                if ($result === false) {
                    $event->stopPropagation();
                }
            }

            if ($event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($event = null)
    {
        return (bool) count($this->getListeners($event));
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($event = null)
    {
        if ($event !== null) {
            return isset($this->sorted[$event]) ? $this->sorted[$event] : $this->sortListeners($event);
        }

        foreach (array_keys($this->listeners) as $event) {
            if (!isset($this->sorted[$event])) {
                $this->sortListeners($event);
            }
        }

        return array_filter($this->sorted);
    }

    /**
     * Sorts all listeners of an event by their priority.
     *
     * @param  string $event
     * @return array
     */
    protected function sortListeners($event)
    {
        $sorted = [];

        if (isset($this->listeners[$event])) {
            krsort($this->listeners[$event]);
            $sorted = call_user_func_array('array_merge', $this->listeners[$event]);
        }

        return $this->sorted[$event] = $sorted;
    }
}
