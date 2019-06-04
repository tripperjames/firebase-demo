<?php

namespace YOOtheme\Util;

class File
{
    use MethodTrait;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var FileLocator
     */
    protected static $locator;

    /**
     * Constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        if (static::isRelative($file)) {
            $file = static::find($file) ?: $file;
        }

        $this->file = strtr($file, '\\', '/');
    }

    /**
     * Gets the base name.
     *
     * @return string
     */
    public function getBasename()
    {
        return pathinfo($this->file, PATHINFO_FILENAME);
    }

    /**
     * Gets the filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return pathinfo($this->file, PATHINFO_BASENAME);
    }

    /**
     * Gets the path without filename.
     *
     * @param  string $path
     * @return string
     */
    public function getPath($path = null)
    {
        if ($path) {
            $path = '/'.ltrim($path, '\/');
        }

        if ($this->isDir()) {
            return $this->file.$path;
        }

        return pathinfo($this->file, PATHINFO_DIRNAME).$path;
    }

    /**
     * Gets the path to the file.
     *
     * @return string
     */
    public function getPathname()
    {
        return $this->file;
    }

    /**
     * Gets the file extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    /**
     * Gets the last access time.
     *
     * @return int
     */
    public function getATime()
    {
        return fileatime($this->file);
    }

    /**
     * Gets the inode change time.
     *
     * @return int
     */
    public function getCTime()
    {
        return filectime($this->file);
    }

    /**
     * Gets the last modified time.
     *
     * @return int
     */
    public function getMTime()
    {
        return filemtime($this->file);
    }

    /**
     * Gets the file size.
     *
     * @return string
     */
    public function getSize()
    {
        return @filesize($this->file);
    }

    /**
     * Gets the contents form file.
     *
     * @return string
     */
    public function getContents()
    {
        return @file_get_contents($this->file);
    }

    /**
     * Writes the contents to file.
     *
     * @param  string $contents
     * @param  int    $flags
     * @return int
     */
    public function putContents($contents, $flags = 0)
    {
        if (!is_dir($path = $this->getPath())) {
            @mkdir($path, 0777, true);
        }

        return @file_put_contents($this->file, $contents, $flags);
    }

    /**
     * Checks if the file exists.
     *
     * @return boolean
     */
    public function exists()
    {
        return @file_exists($this->file);
    }

    /**
     * Checks if the file is a file.
     *
     * @return boolean
     */
    public function isFile()
    {
        return is_file($this->file);
    }

    /**
     * Checks if the file is a link.
     *
     * @return boolean
     */
    public function isLink()
    {
        return is_link($this->file);
    }

    /**
     * Checks if the file is a directory.
     *
     * @return boolean
     */
    public function isDir()
    {
        return is_dir($this->file);
    }

    /**
     * Gets the path to the file.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->file;
    }

    /**
     * Finds the first matched file.
     *
     * @param  string $name
     * @return string|false
     */
    public static function find($name)
    {
        return static::getLocator()->find($name);
    }

    /**
     * Finds all matching files.
     *
     * @param  string $name
     * @return array
     */
    public static function findAll($name)
    {
        return static::getLocator()->findAll($name);
    }

    /**
     * Gets the file locator.
     *
     * @return FileLocator
     */
    public static function getLocator()
    {
        return static::$locator ?: static::$locator = new FileLocator();
    }

    /**
     * Checks if the file is a relative path.
     *
     * @param  string $file
     * @return bool
     */
    public static function isRelative($file)
    {
        return !static::isAbsolute($file);
    }

    /**
     * Checks if the file is an absolute path.
     *
     * @param  string $file
     * @return boolean
     */
    public static function isAbsolute($file)
    {
        return ($file && $file[0] == '/') || (strpos($file, ':') && preg_match('#^[a-z](:(/|\\\)|[a-z]*://)#i', $file));
    }

    /**
     * Normalizes a file path.
     *
     * @param  string $path
     * @return string
     */
    public static function normalizePath($path)
    {
        $parts = [];

        foreach (explode('/', strtr($path, '\\', '/')) as $part) {
            if ($part == '.') {
                continue;
            } else if ($part == '..') {
                array_pop($parts);
            } else {
                $parts[] = $part;
            }
        }

        return join('/', $parts);
    }

    /**
     * Resolves a sequence of paths or path segments into an absolute path. All path segments are processed from right to left.
     *
     * @param  string ...$paths
     * @return string
     */
    public static function resolvePath()
    {
        $parts = [];

        foreach (array_reverse(func_get_args()) as $path) {

            array_unshift($parts, $path);

            if (static::isAbsolute($path)) {
                break;
            }
        }

        return static::normalizePath(join('/', $parts));
    }
}
