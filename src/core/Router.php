<?php

namespace Core;

class Router
{
    private $routes = [];

    public function add($uri, $handler)
    {
        $this->routes[$uri] = $handler;
    }

    public function dispatch($uri)
    {
        $uriPath = strtok($uri, '?');
        $action = $this->getActionFromUri($uriPath, $this->getQueryParams($uri));

        if ($this->routes[$action]) {
            $handler = $this->routes[$action];
            return $handler();
        }

        return;
    }

    private function getQueryParams($uri)
    {
        $queryString = parse_url($uri, PHP_URL_QUERY);
        $queryParams = [];

        if ($queryString !== null) {
            parse_str($queryString, $queryParams);
        }

        return $queryParams;
    }
    private function getActionFromUri($uriPath, $queryParams)
    {
        if (isset($queryParams['action']) && !empty($queryParams['action'])) {
            return DIRECTORY_SEPARATOR . $queryParams['action'];
        }
        return $uriPath;
    }
}
