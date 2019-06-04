<?php

namespace YOOtheme\Http;

class Uri extends Message\Uri
{
    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * Creates an instance from server globals.
     *
     * @param  array $server
     * @return static
     */
    public static function fromGlobals(array $server = [])
    {
        if (!$server) {
            $server = $_SERVER;
        }

        $uri = parent::fromGlobals($server);
        $path = urldecode($uri->getPath());
        $script = parse_url($server['SCRIPT_NAME'], PHP_URL_PATH);
        $scriptDir = dirname($script);

        if (stripos($path, $script) === 0) {
            $uri = $uri->withBasePath($script);
        } elseif ($scriptDir !== '/' && stripos($path, $scriptDir) === 0) {
            $uri = $uri->withBasePath($scriptDir);
        }

        return $uri;
    }

    /**
     * Creates an instance from string.
     *
     * @param  string $url
     * @param  string $basePath
     * @return static
     */
    public static function fromString($url, $basePath = '')
    {
        $uri = new static($url);

        if ($basePath) {
            $uri = $uri->withBasePath($basePath);
        }

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        if ($this->basePath && substr($this->path, 0, 1) != '/') {
            return $this->basePath . '/' . $this->path;
        }

        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath($path)
    {
        $path = static::filterPath($path);

        if ($basePath = $this->basePath and substr($path, 0, 1) == '/') {
            if (strpos($path, $basePath) === 0) {
                $path = ltrim(substr($path, strlen($basePath)), '/');
            } else {
                $basePath = '';
            }
        }

        $clone = clone $this;
        $clone->path = $path;
        $clone->basePath = $basePath;

        return $clone;
    }

    /**
     * Retrieve the base path segment of the URI.
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Return the fully qualified base URL.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        $scheme = $this->getScheme();
        $basePath = $this->getBasePath();
        $authority = $this->getAuthority();

        return ($scheme ? $scheme . ':' : '') . ($authority ? '//' . $authority : '') . rtrim($basePath, '/');
    }

    /**
     * Return an instance with the base path.
     *
     * @param  string $basePath
     * @return self
     */
    public function withBasePath($basePath)
    {
        $clone = clone $this;
        $clone->basePath = static::filterPath(rtrim('/' . trim($basePath, '/'), '/'));

        return $clone;
    }

    /**
     * Retrieve query string arguments.
     *
     * @return array
     */
    public function getQueryParams()
    {
        parse_str($this->getQuery(), $query);

        return $query;
    }

    /**
     * Retrieve a value from query string arguments.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getQueryParam($key, $default = null)
    {
        $query = $this->getQueryParams();

        return isset($query[$key]) ? $query[$key] : $default;
    }

    /**
     * Return an instance with the specified query parameters.
     *
     * @param  array $parameters
     * @return self
     */
    public function withQueryParams(array $parameters)
    {
        return $this->withQuery(http_build_query($parameters, '', '&'));
    }
}
