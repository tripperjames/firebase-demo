<?php

namespace YOOtheme;

use YOOtheme\Util\File;
use YOOtheme\Util\Str;

class ModuleManager implements \IteratorAggregate
{
    /**
     * @var \SplStack
     */
    protected $loader;

    /**
     * @var boolean
     */
    protected $locked;

    /**
     * @var array
     */
    protected $modules = [];

    /**
     * @var array
     */
    protected $registered = [];

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Constructor.
     *
     * @param callable $kernel
     * @param array    $arguments
     */
    public function __construct(callable $kernel, array $arguments = [])
    {
        $this->loader = new \SplStack();
        $this->loader->push($kernel);
        $this->arguments = $arguments;
    }

    /**
     * Get shortcut.
     *
     * @see get()
     */
    public function __invoke($name)
    {
        return $this->get($name);
    }

    /**
     * Gets a module by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->modules[$name]) ? $this->modules[$name] : null;
    }

    /**
     * Gets all modules.
     *
     * @return array
     */
    public function all()
    {
        return $this->modules;
    }

    /**
     * Loads modules by name.
     *
     * @param  string|string[] $modules
     * @param  string|true     $basePath
     * @return $this
     */
    public function load($modules, $basePath = null)
    {
        if ($basePath) {

            if ($modules = $this->resolveModules($modules, is_string($basePath) ? $basePath : null)) {
                $this->registered = array_replace($this->registered, $modules);
            }

            $modules = array_keys($modules);
        }

        $resolved = [];

        foreach ((array) $modules as $name) {

            if (!isset($this->registered[$name])) {
                throw new \RuntimeException("Undefined module: {$name}");
            }

            $this->resolveRequirements($this->registered[$name], $resolved);
        }

        $resolved = array_diff_key($resolved, $this->modules);

        foreach ($resolved as $name => $module) {

            $this->locked = true;
            $load = $this->loader->top();
            $module = $load($module);
            $this->locked = false;

            if (is_callable($module)) {
                call_user_func_array($module, $this->arguments);
            }

            $this->modules[$name] = $module;
        }

        return $this;
    }

    /**
     * Registers modules from path(s).
     *
     * @param  string|string[] $paths
     * @param  string          $basePath
     * @return self
     */
    public function register($paths, $basePath = null)
    {
        if ($modules = $this->resolveModules($paths, $basePath)) {
            $this->registered = array_replace($this->registered, $modules);
        }

        return $this;
    }

    /**
     * Adds a module loader.
     *
     * @param  callable $loader
     * @param  string   $filter
     * @return self
     */
    public function addLoader(callable $loader, $filter = null)
    {
        if ($this->locked) {
            throw new \RuntimeException('Loader can’t be added once the stack is dequeuing');
        }

        $next = $this->loader->top();

        $this->loader->push(function (array $module) use ($loader, $filter, $next) {

            if ($filter && !Str::is($filter, $module['name'])) {
                return $next($module);
            }

            return $loader($module, $next, $this);
        });

        return $this;
    }

    /**
     * Gets the iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->modules);
    }

    /**
     * Resolves modules.
     *
     * @param  string|string[] $paths
     * @param  string          $basePath
     * @return array
     */
    protected function resolveModules($paths, $basePath = null)
    {
        $modules = [];

        foreach ((array) $paths as $name => $path) {

            $files = glob($this->resolvePath($path, $basePath), GLOB_NOSORT) ?: [];

            foreach ($files as $file) {

                if (!is_array($module = @include($file)) || !isset($module['name'])) {
                    continue;
                }

                $module['path'] = strtr(dirname($file), '\\', '/');
                $modules[$module['name']] = $module;
            }
        }

        return $modules;
    }

    /**
     * Resolves module includes.
     *
     * @param  array $module
     * @return array
     */
    protected function resolveIncludes(array $module)
    {
        $modules = [];

        if (isset($module['include'])) {
            foreach ((array) $module['include'] as $name => $include) {
                if (is_string($include)) {
                    $modules = array_replace($modules, $this->resolveModules($include, $module['path']));
                } elseif (is_array($include)) {
                    $modules[$name] = array_replace(['name' => $name, 'path' => $module['path']], $include);
                }
            }
        }

        return $modules;
    }

    /**
     * Resolves module requirements.
     *
     * @param array $module
     * @param array $resolved
     * @param array $unresolved
     *
     * @throws \RuntimeException
     */
    protected function resolveRequirements(array $module, array &$resolved = [], array &$unresolved = [])
    {
        $unresolved[$module['name']] = $module;

        if ($modules = $this->resolveIncludes($module)) {
            $this->registered = array_replace($this->registered, $modules);
        }

        if (isset($module['require'])) {
            foreach ((array) $module['require'] as $required) {
                if (!isset($resolved[$required])) {

                    if (isset($unresolved[$required])) {
                        throw new \RuntimeException(sprintf('Circular requirement "%s > %s" detected.', $module['name'], $required));
                    }

                    if (isset($this->registered[$required])) {
                        $this->resolveRequirements($this->registered[$required], $resolved, $unresolved);
                    }
                }
            }
        }

        $resolved[$module['name']] = $module;
        unset($unresolved[$module['name']]);

        foreach (array_diff_key($modules, $resolved) as $include) {
            $this->resolveRequirements($include, $resolved, $unresolved);
        }
    }

    /**
     * Resolves a absolute path to a given base path.
     *
     * @param  string $path
     * @param  string $basePath
     * @return string
     */
    protected function resolvePath($path, $basePath = null)
    {
        if (File::isRelative($path)) {
            $path = "$basePath/$path";
        }

        $path = strtr($path, '\\', '/');
        $pattern = '#/*[^/\.]+/\.\.#Uu';

        while (preg_match($pattern, $path, $matches)) {
            $path = str_replace($matches[0], '', $path);
        }

        return $path;
    }
}
