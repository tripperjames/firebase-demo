<?php

namespace YOOtheme;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel
{
    use ContainerTrait;

    const FOUND = 1;
    const NOT_FOUND = 0;
    const METHOD_NOT_ALLOWED = 2;

    /**
     * @var \SplStack
     */
    protected $stack;

    /**
     * @var boolean
     */
    protected $locked;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->stack = new \SplStack();
        $this->stack->push($this);
    }

    /**
     * Dispatches router with request and response.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        try {

            $routeInfo = $request->getAttribute('routeInfo');

            if ($routeInfo[0] === static::FOUND) {
                $response = $this->handleFound($request, $response, array_map('urldecode', $routeInfo[2]));
            } elseif ($routeInfo[0] === static::METHOD_NOT_ALLOWED) {
                throw new Http\Exception(405);
            } else {
                throw new Http\Exception(404);
            }

        } catch (\Exception $e) {
            $response = $this->handleException($e, $request, $response);
        }

        return $response;
    }

    /**
     * Aborts a request with an exception.
     *
     * @param  int    $code
     * @param  string $message
     * @throws Http\Exception
     */
    public function abort($code, $message = '')
    {
        throw new Http\Exception($code, $message);
    }

    /**
     * Handles a request and converts it to a response.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @param  boolean                $send
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response, $send = true)
    {
        try {

            $start = $this->stack->top();
            $this->locked = true;
            $response = $start($this->app['router']->dispatch($request), $response);
            $this->locked = false;

        } catch (\Exception $e) {
            $response = $this->handleException($e, $request, $response);
        }

        return $send ? $response->send() : $response;
    }

    /**
     * Handles a found route.
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @param  array                  $params
     * @return ResponseInterface
     */
    public function handleFound(ServerRequestInterface $request, ResponseInterface $response, array $params = [])
    {
        $args = [];
        $params = array_replace(compact('request', 'response'), $params);
        $callable = $this->resolveCallable($request->getAttribute('route')->getCallable());

        foreach ($this->resolveParameters($callable) as $param) {

            $name = $param->getName();

            if (isset($params[$name])) {
                $args[] = $params[$name];
                continue;
            }

            if ($request->getParam($name) !== null) {
                $args[] = $request->getParam($name);
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

            if (is_array($callable)) {
                $handler = sprintf('%s::%s()', get_class($callable[0]), $callable[1]);
            } elseif (is_object($callable)) {
                $handler = get_class($callable);
            } else {
                $handler = $callable;
            }

            throw new \RuntimeException("Handler '{$handler}' requires that you provide a value for the '{$name}' argument");
        }

        $result = call_user_func_array($callable, $args);

        if ($result instanceof ResponseInterface) {
            $response = $result;
        } elseif (is_string($result) || (is_object($result) && method_exists($result, '__toString'))) {
            $response->write((string) $result);
        }

        return $response;
    }

    /**
     * Handles an exception.
     *
     * @param  \Exception             $e
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface      $response
     * @return ResponseInterface
     */
    protected function handleException(\Exception $e, ServerRequestInterface $request, ResponseInterface $response)
    {
        $event = $this->app->trigger('error', [$request, $response, $e]);

        if ($event->result instanceof ResponseInterface) {
            return $event->result;
        }

        if ($e instanceof Http\Exception) {
            return $response->withStatus($e->getCode(), $e->getMessage());
        }

        return $response->withStatus(500, $e->getMessage());
    }

    /**
     * Adds a middleware callable.
     *
     * @param  callable $callable
     * @return self
     */
    public function addMiddleware(callable $callable)
    {
        if ($this->locked) {
            throw new \RuntimeException('Middleware can’t be added once the stack is dequeuing');
        }

        $next = $this->stack->top();

        $this->stack->push(function (ServerRequestInterface $request, ResponseInterface $response) use ($callable, $next) {

            $result = $callable($request, $response, $next);

            if (!$result instanceof ResponseInterface) {
                throw new \UnexpectedValueException('Middleware must return instance of Psr\Http\Message\ResponseInterface');
            }

            return $result;
        });

        return $this;
    }

    /**
     * Resolves a string to a callable that can be dispatched.
     *
     * @param  callable|string $callable
     * @return callable
     */
    public function resolveCallable($callable)
    {
        $pattern = '!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!';

        if (is_callable($callable)) {
            return $callable;
        }

        if (is_string($callable) && preg_match($pattern, $callable, $matches)) {

            list(, $class, $method) = $matches;

            if (isset($this->app[$class])) {
                return [$this->app[$class], $method];
            }

            if (class_exists($class)) {
                return [new $class, $method];
            }
        }

        throw new \RuntimeException("Route callable is not resolvable");
    }

    /**
     * Resolves method parameters from a callable.
     *
     * @param  callable $callable
     * @return array
     */
    public function resolveParameters(callable $callable)
    {
        if (is_array($callable)) {
            $refl = new \ReflectionMethod($callable[0], $callable[1]);
        } elseif (is_object($callable) && !$callable instanceof \Closure) {
            $refl = (new \ReflectionObject($callable))->getMethod('__invoke');
        } else {
            $refl = new \ReflectionFunction($callable);
        }

        return $refl->getParameters();
    }
}
