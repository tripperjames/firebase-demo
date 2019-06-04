<?php

namespace YOOtheme;

use YOOtheme\Util\Arr;

trait ContainerTrait
{
    /**
     * Injects dynamic properties.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        $property = $name;

        if (isset($this->inject, $this->inject[$name])) {
            $name = $this->inject[$name];
        }

        if ($this instanceof \ArrayAccess && $this->offsetExists($name)) {
            $value = $this->offsetGet($name);
        } elseif (strpos($name, '::') && is_callable($name)) {
            $value = call_user_func($name);
        } else {
            $value = Arr::get(Container::$providers, $name);
        }

        if ($value !== null) {
            return $this->$property = $value;
        }
    }
}
