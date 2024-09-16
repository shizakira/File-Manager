<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function add(string $uri, callable $handler): void
    {
        $this->routes[$uri] = $handler;
    }

    public function dispatch(string $uri): ?array
    {
        $uriPath = strtok($uri, '?');
        $action = $this->getActionFromUri($uriPath, $this->getQueryParams($uri));

        if ($this->routes[$action]) {
            $handler = $this->routes[$action];
            return $handler();
        }

        return null;
    }

    private function getQueryParams(string $uri): array
    {
        $queryString = parse_url($uri, PHP_URL_QUERY);
        $queryParams = [];

        if ($queryString !== null) {
            parse_str($queryString, $queryParams);
        }

        return $queryParams;
    }
    private function getActionFromUri(string $uriPath, array $queryParams): string
    {
        if (isset($queryParams['action']) && !empty($queryParams['action'])) {
            return DIRECTORY_SEPARATOR . $queryParams['action'];
        }
        return $uriPath;
    }
}
