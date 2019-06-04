<?php

namespace YOOtheme\Util;

class Path
{
    use MethodTrait;

    /**
     * @var array
     */
    public static $aliases = [];

    /**
     * @var array
     */
    public static $normalized = [];

    /**
     * Adds a path alias.
     *
     * @param string $alias
     * @param string $path
     */
    public static function alias($alias, $path)
    {
        $path = static::normalize($path);

        if (!preg_match('/^@\w+/', $alias, $matches)) {
            throw new \InvalidArgumentException("The alias '{$alias}' must start with @");
        }

        if ($path && $path[0] == '@') {
            $path = static::resolve($path);
        }

        static::$aliases[$matches[0]][$alias] = rtrim($path, '/');

        uksort(static::$aliases[$matches[0]], function ($alias1, $alias2) {

            list($base1) = explode('/', $alias1, 2);
            list($base2) = explode('/', $alias2, 2);

            return ($result = strcmp($base2, $base1)) ? $result : strcmp($alias2, $alias1);
        });
    }

    /**
     * Resolve a path with alias.
     *
     * @param  string $path
     * @return string
     */
    public static function resolve($path)
    {
        $path = static::normalize($path);

        list($alias) = explode('/', $path, 2);

        if (isset(static::$aliases[$alias])) {
            foreach (static::$aliases[$alias] as $name => $aliasPath) {
                if (strpos("{$path}/", "{$name}/") === 0) {
                    return $aliasPath . substr($path, strlen($name));
                }
            }
        }

        return $path;
    }

    /**
     * Gets the relative path to a given base path.
     *
     * @param  string $path
     * @param  string $basePath
     * @return string
     */
    public static function relative($path, $basePath)
    {
        $path = static::normalize($path);
        $basePath = static::normalize($basePath);

        list($root, $relativePath) = self::split($path);
        list($baseRoot, $relativeBasePath) = self::split($basePath);

        if ($root === '' && $baseRoot !== '') {
            return $relativeBasePath === '' ? ltrim($relativePath, './') : $relativePath;
        }

        if ($root !== '' && $baseRoot === '') {
            throw new \InvalidArgumentException("The absolute path '{$path}' can\'t be made relative to the relative path '{$basePath}'");
        }

        if ($baseRoot && $baseRoot !== $root) {
            throw new \InvalidArgumentException("The path '{$path}' can\'t be made relative to '{$basePath}', because the roots are different ('{$root}' and '{$baseRoot}').");
        }

        if ($relativeBasePath === '') {
            return $relativePath;
        }

        $parts = explode('/', $relativePath);
        $baseParts = explode('/', $relativeBasePath);

        $match = true;
        $prefix = '';

        foreach ($baseParts as $i => $basePart) {

            if ($match && isset($parts[$i]) && $basePart === $parts[$i]) {
                unset($parts[$i]);
                continue;
            }

            $match = false;
            $prefix .= '../';
        }

        return rtrim($prefix.join('/', $parts), '/');
    }

    /**
     * Normalizes a path, resolving '..' and '.' segments.
     *
     * @param  string $path
     * @return string
     */
    public static function normalize($path)
    {
        if ($path === '') {
            return '';
        }

        if (isset(static::$normalized[$path])) {
            return static::$normalized[$path];
        }

        list($root, $relativePath) = static::split($path);

        $parts = [];

        foreach (explode('/', $relativePath) as $part) {

            if ($part == '.' || $part === '') {
                continue;
            }

            if ($part == '..' && $parts && $parts[count($parts) - 1] != '..') {
                array_pop($parts);
                continue;
            }

            if ($part != '..' || $root === '') {
                $parts[] = $part;
            }
        }

        return static::$normalized[$path] = $root.join('/', $parts);
    }

    /**
     * Splits a path into root and relative path.
     *
     * @param  string $path
     * @return array
     */
    public static function split($path)
    {
        $path = strtr($path, '\\', '/');
        $root = '';

        if (strpos($path, ':')) {

            // is scheme?
            if (preg_match('/^([a-z]*:\/\/)(.*)/i', $path, $matches)) {
                list(, $root, $path) = $matches;
            }

            // is windows?
            if (preg_match('/(^[a-z]:)(?:\/|$)(.*)/i', $path, $matches)) {
                return ["{$root}{$matches[1]}/", $matches[2]];
            }
        }

        // is root?
        if ($path && $path[0] == '/') {
            return ["{$root}/", substr($path, 1)];
        }

        return [$root, $path];
    }

    /**
     * Checks if the path is a relative.
     *
     * @param  string $path
     * @return bool
     */
    public static function isRelative($path)
    {
        return static::split($path)[0] == '';
    }

    /**
     * Checks if the path is an absolute.
     *
     * @param  string $path
     * @return boolean
     */
    public static function isAbsolute($path)
    {
        return static::split($path)[0] != '';
    }
}
