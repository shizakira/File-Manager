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
        $queryString = parse_url($uri, PHP_URL_QUERY);

        $queryParams = [];
        if ($queryString !== null) {
            parse_str($queryString, $queryParams);
        }

        if (isset($queryParams['action']) && !empty($queryParams['action'])) {
            $action = '/' . $queryParams['action'];
        } else {
            $action = $uriPath;
        }

        if (isset($this->routes[$action])) {
            $handler = $this->routes[$action];
            return $handler();
        } else {
            http_response_code(404);
            return;
        }
    }
}
