<?php

namespace YOOtheme\Util;

class FileLocator
{
    const NAMESPACE_DEFAULT = '__default__';

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->paths[self::NAMESPACE_DEFAULT] = [];
    }

    /**
     * Find shortcut.
     *
     * @param  string $name
     * @return string|false
     */
    public function __invoke($name)
    {
        return $this->find($name);
    }

    /**
     * Finds the first matched file.
     *
     * @param  string $name
     * @return string|false
     */
    public function find($name)
    {
        if ($paths = $this->findAll($name)) {
            return $paths[0];
        }

        return false;
    }

    /**
     * Finds all matching files.
     *
     * @param  string $name
     * @return array
     */
    public function findAll($name)
    {
        $name = $this->normalizeName($name);
        $paths = [];

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        list($namespace, $file) = $this->parseNamespace($name);

        if (!isset($this->paths[$namespace])) {
            throw new \InvalidArgumentException("No paths for namespace \"{$namespace}\" for {$name}");
        }

        foreach ($this->paths[$namespace] as $parts) {

            list($prefix, $path) = $parts;

            if ($length = strlen($prefix) and strpos($file, $prefix) !== 0) {
                continue;
            }

            if (($p = substr($file, $length)) !== false) {
                $path .= '/'.ltrim($p, '\/');
            }

            if (preg_match('/\*|{.+}/', $path) && $files = $this->globFiles($path)) {
                $paths = array_merge($paths, array_map([$this, 'normalizeName'], $files));
            } elseif (@file_exists($path)) {
                $paths[] = $path;
            }
        }

        return $this->cache[$name] = $paths;
    }

    /**
     * Add path(s) to namespace.
     *
     * @param  string|array $paths
     * @param  string       $namespace
     * @return self
     * @throws \InvalidArgumentException
     */
    public function addPath($paths, $namespace = self::NAMESPACE_DEFAULT)
    {
        $parts = explode('/', $namespace, 2);
        $prefix = '';

        if (count($parts) == 2) {
            list($namespace, $prefix) = $parts;
        }

        if (!isset($this->paths[$namespace])) {
            $this->paths[$namespace] = [];
        }

        foreach ((array) $paths as $path) {

            $path = $this->normalizeName(rtrim($path, '\/'));

            if (!is_dir($path)) {
                continue;
            }

            array_unshift($this->paths[$namespace], [$prefix, $path]);
        }

        $this->cache = [];

        return $this;
    }

    /**
     * Gets the namespaces.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return array_keys($this->namespaces);
    }

    /**
     * Parses the namespace.
     *
     * @param  string $name
     * @return array
     * @throws \InvalidArgumentException
     */
    public function parseNamespace($name, $default = self::NAMESPACE_DEFAULT)
    {
        if ($name && $name[0] == '@') {

            $parts = explode('/', substr($name, 1), 2);

            if (count($parts) != 2) {
                throw new \InvalidArgumentException("Invalid name \"{$name}\" (expecting \"@namespace/path\")");
            }

            return $parts;
        }

        return [$default, $name];
    }

    /**
     * Glob files with braces.
     *
     * @param  string $pattern
     * @return array
     */
    protected function globFiles($pattern)
    {
        if (defined('GLOB_BRACE')) {
            return glob($pattern, GLOB_BRACE | GLOB_NOSORT) ?: [];
        }

        $files = [];

        foreach (Str::expandBraces($pattern) as $file) {
            $files = array_merge($files, glob($file) ?: []);
        }

        return $files;
    }

    /**
     * Normalize the filename.
     *
     * @param  string $name
     * @return string
     */
    protected function normalizeName($name)
    {
        return strtr((string) $name, '\\', '/');
    }
}
