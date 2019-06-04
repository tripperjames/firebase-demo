<?php

namespace YOOtheme\Util;

class Collection implements \Countable, \ArrayAccess, \IteratorAggregate, \JsonSerializable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var boolean
     */
    protected $strict = false;

    /**
     * Constructor.
     *
     * @param mixed   $items
     * @param boolean $strict
     */
    public function __construct($items = [], $strict = false)
    {
        $this->items = $this->getArray($items);
        $this->strict = $strict;
    }

    /**
     * Checks if a key exists.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Gets a item value.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $this->offsetGet($key);

        return isset($value) ? $value : $default;
    }

    /**
     * Sets a item value.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return self
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * Merges the collection with given items.
     *
     * @param  mixed $items
     * @param  bool  $recursive
     * @return self
     */
    public function merge($items, $recursive = false)
    {
        $this->items = Arr::merge($this->items, $this->getArray($items), $recursive);

        return $this;
    }

    /**
     * Transform the collection using a callback.
     *
     * @param  callable $callback
     * @return $this
     */
    public function transform(callable $callback)
    {
        $this->items = $this->map($callback)->all();

        return $this;
    }

    /**
     * Removes one or more items.
     *
     * @param  string|array $keys
     * @return self
     */
    public function remove($keys)
    {
        foreach ((array) $keys as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Removes all items.
     *
     * @return self
     */
    public function clear()
    {
        $this->items = [];

        return $this;
    }

    /**
     * Gets all items.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Gets all keys.
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items), $this->strict);
    }

    /**
     * Gets items with numeric keys.
     *
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->items), $this->strict);
    }

    /**
     * Gets items as string separated by separator.
     *
     * @param  string $separator
     * @return string
     */
    public function join($separator = '')
    {
        return join($separator, $this->items);
    }

    /**
     * Gets items as JSON string.
     *
     * @param  int $options
     * @return string
     */
    public function json($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Gets items for JSON encoding.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->items ?: new \stdClass();
    }

    /**
     * Checks if the given value exists.
     *
     * @param  mixed $value
     * @return bool
     */
    public function contains($value)
    {
        return in_array($value, $this->items);
    }

    /**
     * Checks if all items pass the predicate truth test.
     *
     * @param  array|callable $predicate
     * @return bool
     */
    public function every($predicate)
    {
        return Arr::every($this->items, $predicate);
    }

    /**
     * Checks if some items pass the predicate truth test.
     *
     * @param  array|callable $predicate
     * @return bool
     */
    public function some($predicate)
    {
        return Arr::some($this->items, $predicate);
    }

    /**
     * Counts the items.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Gets the max value of a given key.
     *
     * @param  string|null $key
     * @return mixed
     */
    public function max($key = null)
    {
        return $this->reduce(function ($result, $item) use ($key) {

            $value = Arr::get($item, $key);

            return is_null($result) || $value > $result ? $value : $result;
        });
    }

    /**
     * Gets the min value of a given key.
     *
     * @param  string|null $key
     * @return mixed
     */
    public function min($key = null)
    {
        return $this->reduce(function ($result, $item) use ($key) {

            $value = Arr::get($item, $key);

            return is_null($result) || $value < $result ? $value : $result;
        });
    }

    /**
     * Split the items into chunks.
     *
     * @param  int $size
     * @return static
     */
    public function chunk($size)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = new static($chunk, $this->strict);
        }

        return new static($chunks, $this->strict);
    }

    /**
     * Gets the items with the given keys.
     *
     * @param  array|string $keys
     * @return static
     */
    public function pick($keys)
    {
        return new static(Arr::pick($this->items, $keys), $this->strict);
    }

    /**
     * Gets all items without the given keys.
     *
     * @param  array|string $keys
     * @return static
     */
    public function omit($keys)
    {
        return new static(Arr::omit($this->items, $keys), $this->strict);
    }

    /**
     * Gets the results of the given callback function.
     *
     * @param  callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $items = [];

        foreach ($this->items as $key => $value) {
            $items[$key] = $callback($value, $key);
        }

        return new static($items, $this->strict);
    }

    /**
     * Executes a callback function on each item.
     *
     * @param  callable $callback
     * @return self
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Reduces the collection to a single value.
     *
     * @param  callable $callback
     * @param  mixed    $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * Gets the items that are not present in given items.
     *
     * @param  mixed $items
     * @return static
     */
    public function diff($items)
    {
        return new static(array_diff($this->items, $this->getArray($items)), $this->strict);
    }

    /**
     * Gets the items whose keys are not present in given items.
     *
     * @param  mixed $items
     * @return static
     */
    public function diffKeys($items)
    {
        return new static(array_diff_key($this->items, $this->getArray($items)), $this->strict);
    }

    /**
     * Gets the first item passing the predicate truth test.
     *
     * @param  array|callable $predicate
     * @return mixed
     */
    public function find($predicate)
    {
        return Arr::find($this->items, $predicate);
    }

    /**
     * Gets the all items that pass the predicate truth test.
     *
     * @param  array|callable $predicate
     * @return static
     */
    public function filter($predicate = null)
    {
        return new static(Arr::filter($this->items, $predicate), $this->strict);
    }

    /**
     * Gets the items that do not pass a given truth test.
     *
     * @param  array|callable $predicate
     * @return static
     */
    public function reject($predicate = null)
    {
        return new static(array_diff_key($this->items, Arr::filter($this->items, $predicate)), $this->strict);
    }

    /**
     * Gets the items in reverse order.
     *
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items, true), $this->strict);
    }

    /**
     * Gets a slice of the collection array.
     *
     * @param  int $offset
     * @param  int $length
     * @return static
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true), $this->strict);
    }

    /**
     * Gets a portion of the collection array and replaces it with something else.
     *
     * @param  int   $offset
     * @param  int   $length
     * @param  mixed $replacement
     * @return static
     */
    public function splice($offset, $length = 0, $replacement = [])
    {
        if (func_num_args() == 1) {
            return new static(array_splice($this->items, $offset), $this->strict);
        }

        return new static(array_splice($this->items, $offset, $length, $replacement), $this->strict);
    }

    /**
     * Checks if an offset exists.
     *
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        if ($this->strict) {
            return array_key_exists($key, $this->items);
        }

        return Arr::has($this->items, $key);
    }

    /**
     * Gets an item value.
     *
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        if ($this->strict) {
            return $this->offsetExists($key) ? $this->items[$key] : null;
        }

        return Arr::get($this->items, $key);
    }

    /**
     * Sets an item value.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function offsetSet($key, $value)
    {
        if ($this->strict) {
            $this->items[$key] = $value;
        } else {
            Arr::set($this->items, $key, $value);
        }
    }

    /**
     * Removes an item.
     *
     * @param string $key
     */
    public function offsetUnset($key)
    {
        if ($this->strict) {
            unset($this->items[$key]);
        } else {
            Arr::remove($this->items, $key);
        }
    }

    /**
     * Gets the items as string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->json() ?: '';
    }

    /**
     * Gets an iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Gets an array of items.
     *
     * @param  mixed $items
     * @return array
     */
    protected function getArray($items)
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof self) {
            return $items->all();
        } elseif ($items instanceof \ArrayObject) {
            return $items->getArrayCopy();
        } elseif ($items instanceof \JsonSerializable) {
            return $items->jsonSerialize();
        } elseif ($items instanceof \Traversable) {
            return iterator_to_array($items);
        }

        return (array) $items;
    }
}
