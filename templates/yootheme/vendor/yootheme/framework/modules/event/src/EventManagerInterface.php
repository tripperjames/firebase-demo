<?php

namespace YOOtheme;

interface EventManagerInterface
{
    /**
     * Adds an event listener.
     *
     * @param  string   $event
     * @param  callable $listener
     * @param  int      $priority
     * @return self
     */
    public function on($event, $listener, $priority = 0);

    /**
     * Removes one or more event listeners.
     *
     * @param  string   $event
     * @param  callable $listener
     * @return self
     */
    public function off($event, $listener = null);

    /**
     * Adds an event subscriber.
     *
     * @param  EventSubscriberInterface $subscriber
     * @return self
     */
    public function subscribe(EventSubscriberInterface $subscriber);

    /**
     * Removes an event subscriber.
     *
     * @param  EventSubscriberInterface $subscriber
     * @return self
     */
    public function unsubscribe(EventSubscriberInterface $subscriber);

    /**
     * Triggers an event.
     *
     * @param  string $event
     * @param  array  $parameters
     * @return EventInterface
     */
    public function trigger($event, array $parameters = []);

    /**
     * Checks if a event has listeners.
     *
     * @param  string $event
     * @return bool
     */
    public function hasListeners($event = null);

    /**
     * Gets all listeners of an event.
     *
     * @param  string $event
     * @return array
     */
    public function getListeners($event = null);
}
