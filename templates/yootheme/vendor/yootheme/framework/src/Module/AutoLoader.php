<?php

namespace YOOtheme\Module;

use YOOtheme\Util\File;

class AutoLoader
{
    /**
     * @var ClassLoader
     */
    protected $loader;

    /**
     * Constructor.
     *
     * @param ClassLoader $loader
     */
    public function __construct($loader)
    {
        $this->loader = $loader;
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
        if (isset($options['autoload'])) {
            foreach ($options['autoload'] as $namespace => $path) {
                $this->loader->addPsr4($namespace, $this->resolvePath($path, $options['path']));
            }
        }

        return $next($options);
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

        return strtr($path, '\\', '/');
    }
}
