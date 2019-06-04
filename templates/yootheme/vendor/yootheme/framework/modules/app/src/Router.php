<?php

namespace YOOtheme;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Constructor.
     *
     * @param RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Dispatches router for a request.
     *
     * @param  ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $path = '/'.trim($request->getQueryParam('p'), '/');

        foreach ($this->routes->getIndex() as $route) {

            if ($route->getMethods() && !in_array($request->getMethod(), $route->getMethods())) {
                continue;
            }

            if (preg_match($this->getPattern($route), $path, $matches)) {

                $params = [];

                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                foreach ($route->getAttributes() as $name => $value) {
                    $request->setAttribute($name, $value);
                }

                $request->setAttribute('route', $route);
                $request->setAttribute('routeInfo', [1, $route->getName(), $params]);

                return $request;
            }
        }

        return $request->setAttribute('routeInfo', [0]);
    }

    /**
     * Gets the route regex pattern.
     *
     * @param  Route $route
     * @return string
     */
    protected function getPattern(Route $route)
    {
        return '#^' . preg_replace_callback('#\{(\w+)\}#', function ($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $route->getPath()) . '$#';
    }
}
