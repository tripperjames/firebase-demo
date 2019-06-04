<?php

namespace YOOtheme;

trait EventTrait
{
    /**
     * @see EventManager::on
     */
    public function on($event, $listener, $priority = 0)
    {
        $this['events']->on($event, $listener, $priority);

        return $this;
    }

    /**
     * @see EventManager::off
     */
    public function off($event, $listener = null)
    {
        $this['events']->on($event, $listener);

        return $this;
    }

    /**
     * @see EventManager::subscribe
     */
    public function subscribe(EventSubscriberInterface $subscriber)
    {
        $this['events']->subscribe($subscriber);

        return $this;
    }

    /**
     * @see EventManager::trigger
     */
    public function trigger($event, array $arguments = [])
    {
        return $this['events']->trigger($event, $arguments);
    }
}
