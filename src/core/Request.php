<?php

namespace Core;

class Request
{
    public static function get(string $key, ?string $default = null): ?string
    {
        return $_GET[$key] ?? $default;
    }

    public static function post(string $key, ?string $default = null): ?string
    {
        if (self::isJsonRequest()) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $data[$key] ?? $default;
        }
        return $_POST[$key] ?? $default;
    }

    public static function isJsonRequest(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }
}
