<?php

namespace YOOtheme;

use Psr\Http\Message\UriInterface;
use YOOtheme\Http\Uri;
use YOOtheme\Util\Str;

class UrlGenerator
{
    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var \SplStack
     */
    protected $resolver;

    /**
     * Constructor.
     *
     * @param UriInterface $uri
     */
    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
        $this->resolver = new \SplStack();
        $this->resolver->push([$this, 'generateUrl']);
    }

    /**
     * Gets the URL base path.
     *
     * @param  bool $secure
     * @return string
     */
    public function base($secure = null)
    {
        if (is_null($secure)) {
            return $this->uri->getBasePath();
        }

        return $this->uri->withScheme($secure ? 'https' : 'http')->getBaseUrl();
    }

    /**
     * Generates a URL to a path.
     *
     * @param  string $path
     * @param  array  $parameters
     * @param  bool   $secure
     * @return string
     */
    public function to($path, array $parameters = [], $secure = null)
    {
        try {

            if (empty($parameters) && is_null($secure) && $this->validateUrl($path)) {
                return $path;
            }

            $resolve = $this->resolver->top();

            return (string) $resolve($path, $parameters, $secure);

        } catch (\Exception $e) {

            return false;
        }
    }

    /**
     * Generates a URL to a route.
     *
     * @param  string $pattern
     * @param  array  $parameters
     * @param  bool   $secure
     * @return string
     */
    public function route($pattern = '', array $parameters = [], $secure = null)
    {
        throw new \BadMethodCallException('Must be implemented.');
    }

    /**
     * Adds a URL resolver.
     *
     * @param  callable $resolver
     * @return self
     */
    public function addResolver(callable $resolver)
    {
        $next = $this->resolver->top();

        $this->resolver->push(function ($path, $parameters, $secure) use ($resolver, $next) {
            return $resolver($path, $parameters, $secure, $next);
        });

        return $this;
    }

    /**
     * Generates a URL to a path.
     *
     * @param  string $path
     * @param  array  $parameters
     * @param  bool   $secure
     * @return Uri
     */
    public function generateUrl($path, array $parameters = [], $secure = null)
    {
        $url = Uri::fromString($path, $this->uri->getBasePath());
        $abs = Str::startsWith($url->getPath(), '/') || $url->getHost();

        if (!$abs) {
            $url = $url->withPath('/'.$url->getPath());
        }

        if ($query = array_replace($url->getQueryParams(), $parameters)) {
            $url = $url->withQueryParams($query);
        }

        if (is_bool($secure)) {

            if (!$url->getHost()) {
                $url = $url->withHost($this->uri->getHost())->withPort($this->uri->getPort());
            }

            $url = $url->withScheme($secure ? 'https' : 'http');
        }

        return $url;
    }

    /**
     * Checks if the given path is a valid URL.
     *
     * @param  string $path
     * @return bool
     */
    protected function validateUrl($path)
    {
        $valid = ['http://', 'https://', 'mailto:', 'tel:', '//', '#'];

        return Str::startsWith($path, $valid) || filter_var($path, FILTER_VALIDATE_URL);
    }
}
