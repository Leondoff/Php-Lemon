<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Kernel\Container;
use Lemon\Routing\Exceptions\RouteException;
use Lemon\Support\Types\Arr;

class Collection
{
    /**
     * List of collected routes.
     *
     * @var array<string, Route|static>
     */
    private array $routes;

    /*
        TODO STUFF

        private MiddlewareCollection $middlewares;

        and functions for it okacko

     */

    private string $prefix = '';

    public function __construct(
        private Container $middlewares
    ) {
    }

    public function add(string $path, string $method, callable $action): Route
    {
        if ($this->has($path)) {
            return $this->find($path)->action($method, $action);
        }

        $route = new Route($path, [$method => $action], new MiddlewareCollection($this->middlewares));
        $this->routes[$path] = $route;

        return $route;
    }

    public function find(string $path): Route
    {
        if (!$this->has($path)) {
            throw new RouteException('Route '.$path.' does not exist');
        }

        return $this->routes[$path];
    }

    public function has(string $path): bool
    {
        return Arr::hasKey($this->routes, $path);
    }

    public function collection(self $collection): static
    {
        $this->routes[] = $collection;

        return $this;
    }

    public function prefix(string $prefix = null): string|static
    {
        if (!$prefix) {
            return $this->prefix;
        }

        $this->prefix = $prefix;

        return $this;
    }

    public function dispatch(string $path): ?array
    {
        if ($this->prefix) {
            if (preg_match("^({$this->prefix})(.+)$/", $path, $matches)) {
                $path = $matches[2];
            } else {
                return null;
            }
        }
        foreach ($this->routes as $route) {
            if ($route instanceof Collection) {
                if ($found = $route->dispatch($path)) {
                    return $found;
                }
            }

            if ($route instanceof Route) {
                if ($found = $route->matches($path)) {
                    return [$route, $found];
                }
            }
        }

        return null;
    }
}