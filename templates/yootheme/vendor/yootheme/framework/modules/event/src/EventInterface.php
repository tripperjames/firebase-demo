<?php

namespace YOOtheme;

interface EventInterface
{
    /**
     * Gets event name.
     *
     * @return string
     */
    public function getName();

    /**
     * Stop propagating this event.
     */
    public function stopPropagation();

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped();
}
