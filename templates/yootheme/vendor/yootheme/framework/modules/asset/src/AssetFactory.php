<?php

namespace YOOtheme;

class AssetFactory
{
    /**
     * @var callable
     */
    protected $loader;

    /**
     * @var array
     */
    protected $types = [
        'file' => 'YOOtheme\Asset\FileAsset',
        'string' => 'YOOtheme\Asset\StringAsset',
    ];

    /**
     * @var string
     */
    protected $version;

    /**
     * Constructor.
     *
     * @param callable $loader
     */
    public function __construct(callable $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Returns version number for cache breaking.
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set a version number for cache breaking.
     *
     * @param $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Create an asset instance.
     *
     * @param  string $name
     * @param  mixed  $source
     * @param  mixed  $dependencies
     * @param  mixed  $options
     * @return AssetInterface
     * @throws \InvalidArgumentException
     */
    public function create($name, $source, $dependencies = [], $options = [])
    {
        if (is_string($dependencies)) {
            $dependencies = $dependencies ? [$dependencies] : [];
        }

        if (is_string($options)) {
            $options = ['type' => $options];
        }

        if (!isset($options['type'])) {
            $options['type'] = 'file';
        }

        if ($options['type'] == 'file') {
            if ($options['path'] = call_user_func($this->loader, $source)) {
                $options['version'] = isset($options['version']) ? $options['version'] : $this->version;
            }
        }

        if (isset($this->types[$options['type']])) {

            $class = $this->types[$options['type']];

            return new $class($name, $source, $dependencies, $options);
        }

        throw new \InvalidArgumentException('Unable to determine asset type.');
    }

    /**
     * Registers an asset type.
     *
     * @param  string $name
     * @param  string $class
     * @return self
     */
    public function register($name, $class)
    {
        $this->types[$name] = $class;

        return $this;
    }
}
