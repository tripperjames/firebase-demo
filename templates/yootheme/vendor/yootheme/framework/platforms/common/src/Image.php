<?php

namespace YOOtheme;

use YOOtheme\Image\GDResource;
use YOOtheme\Util\File;

class Image
{
    /**
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $quality = 80;

    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var string
     */
    protected $resourceClass = 'YOOtheme\Image\Resource';

    /**
     * @var array
     */
    protected $operations = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Constructor.
     *
     * @param string $file
     * @param bool   $resource
     */
    public function __construct($file, $resource = true)
    {
        $this->file = strtr($file, '\\', '/');

        if ($file = $this->getFile()) {
            list($this->width, $this->height, $this->type) = ImageProvider::getInfo($file);
        }

        if ($resource && extension_loaded('gd')) {
            $this->resource = GDResource::create($file, $this->type);
            $this->resourceClass = 'YOOtheme\Image\GDResource';
        }
    }

    /**
     * Gets the type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the height.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Gets the width.
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Gets the hash.
     *
     * @return string|false
     */
    public function getHash()
    {
        return $this->operations ? hash('crc32b', (string) $this) : false;
    }

    /**
     * Gets the file.
     *
     * @return string|false
     */
    public function getFile()
    {
        if (File::isRelative($this->file)) {
            return File::find($this->file);
        }

        return file_exists($this->file) ? $this->file : false;
    }

    /**
     * Gets the filename.
     *
     * @param  string $path
     * @return string
     */
    public function getFilename($path = null)
    {
        $hash = $this->getHash();
        $file = pathinfo($this->file);

        if ($path) {
            $path = rtrim($path, '\/') . '/';
        }

        return $path . ($hash ? sprintf('%s-%s.%s', $file['filename'], $hash, $this->type) : $file['basename']);
    }

    /**
     * Retrieve a attribute value.
     *
     * @param  string $name
     * @param  string $default
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Sets a attribute value on the instance.
     *
     * @param  string $name
     * @param  mixed $value
     * @return self
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Checks the protrait orientation.
     *
     * @return boolean
     */
    public function isPortait()
    {
        return $this->height > $this->width;
    }

    /**
     * Checks the landscape orientation.
     *
     * @return boolean
     */
    public function isLandscape()
    {
        return $this->width >= $this->height;
    }

    /**
     * Convert the image.
     *
     * @param  string $type
     * @param  int    $quality
     * @return self
     */
    public function type($type, $quality = 80)
    {
        $image = clone $this;
        $image->type = $type;
        $image->quality = $quality;
        $image->operations[] = ['type', [$type, $quality]];

        return $image;
    }

    /**
     * Crops the image.
     *
     * @param  int $width
     * @param  int $height
     * @param  int $x
     * @param  int $y
     * @return self
     */
    public function crop($width = null, $height = null, $x = 'center', $y = 'center')
    {
        $ratio = $this->width / $this->height;
        $width = $this->parseValue($width, $this->width);
        $height = $this->parseValue($height, $this->height);

        if ($ratio > ($width / $height)) {
            $image = $this->resize(round($height * $ratio), $height);
        } else {
            $image = $this->resize($width, round($width / $ratio));
        }

        if ($x === 'left') {
            $x = 0;
        } elseif ($x === 'right') {
            $x = $image->width - $width;
        } elseif ($x === 'center') {
            $x = ($image->width - $width) / 2;
        }

        if ($y === 'top') {
            $y = 0;
        } elseif ($y === 'bottom') {
            $y = $image->height - $height;
        } elseif ($y === 'center') {
            $y = ($image->height - $height) / 2;
        }

        $image->doCrop($image->width = $width, $image->height = $height, intval($x), intval($y));

        return $image;
    }

    /**
     * Resizes the image.
     *
     * @param  int    $width
     * @param  int    $height
     * @param  string $background
     * @return self
     */
    public function resize($width = null, $height = null, $background = 'crop')
    {
        if ($background == 'cover') {
            return $this->crop($width, $height);
        }

        $image = clone $this;
        $width = $this->parseValue($width, $this->width);
        $height = $this->parseValue($height, $this->height);

        if ($this->width != $width) {
            $scale = $this->width / $width;
        }

        if ($this->height != $height) {
            $scale = isset($scale) ? max($scale, $this->height / $height) : $this->height / $height;
        }

        if (!isset($scale) || !$scale) {
            $scale = 1.0;
        }

        $dstWidth = intval(round($this->width / $scale));
        $dstHeight = intval(round($this->height / $scale));

        if ($background == 'fill') {
            $image->doResize($image->width = $width, $image->height = $height, $width, $height);
        } else if ($background == 'crop') {
            $image->doResize($image->width = $dstWidth, $image->height = $dstHeight, $dstWidth, $dstHeight);
        } else {
            $image->doResize($image->width = $width, $image->height = $height, $dstWidth, $dstHeight, $background);
        }

        return $image;
    }

    /**
     * Thumbnail the image.
     *
     * @param  int  $width
     * @param  int  $height
     * @param  bool $flip
     * @return self
     */
    public function thumbnail($width = null, $height = null, $flip = false)
    {
        if ($flip) {

            $width = strpos($width, '%') ? $this->parseValue($width, $this->width) : $width;
            $height = strpos($height, '%') ? $this->parseValue($height, $this->height) : $height;

            if ($this->isPortait() && $width > $height) {
                list($width, $height) = [$height, $width];
            } elseif ($this->isLandscape() && $height > $width) {
                list($width, $height) = [$height, $width];
            }
        }

        return is_numeric($width) && is_numeric($height) ? $this->crop($width, $height) : $this->resize($width, $height);
    }

    /**
     * Apply multiple operations.
     *
     * @param  array $operations
     * @return self
     */
    public function apply(array $operations)
    {
        $image = $this;

        foreach ($operations as $name => $args) {

            if (is_int($name)) {
                $name = $args[0];
                $args = $args[1];
            }

            if (is_string($args)) {
                $args = explode(',', trim($args));
            }

            $image = call_user_func_array([$image, $name], $args);
        }

        return $image;
    }

    /**
     * Saves the image.
     *
     * @param  string $file
     * @param  string $type
     * @param  int    $quality
     * @return string|false
     */
    public function save($file, $type = null, $quality = null)
    {
        if (!$type) {
            $type = $this->type;
        }

        if (!$quality) {
            $quality = $this->quality;
        }

        return call_user_func("{$this->resourceClass}::save", $this->resource, $file, $type, $quality) ? $file : false;
    }

    /**
     * Calls static resource methods.
     *
     * @param  string $name
     * @param  array  $args
     * @return self
     */
    public function __call($name, $args)
    {
        $method = [$this->resourceClass, $name];

        // call image resource
        if (is_callable($method)) {
            $this->resource = call_user_func_array($method, array_merge([$this->resource], $args));
            $this->operations[] = [$name, $args];
        }

        return $this;
    }

    /**
     * Gets image as json string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode([$this->file, $this->operations]);
    }

    /**
     * Parses a percent value.
     *
     * @param  mixed $value
     * @param  int   $baseValue
     * @return int
     */
    protected function parseValue($value, $baseValue)
    {
        if (preg_match('/%$/', $value)) {
            $value = round($baseValue * (intval($value) / 100.0));
        }

        return intval($value) ?: $baseValue;
    }
}
