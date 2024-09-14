<?php

namespace Core;

class Request
{
    public static function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public static function post($key, $default = null)
    {
        if (self::isJsonRequest()) {
            $data = json_decode(file_get_contents('php://input'), true);
            return $data[$key] ?? $default;
        }
        return $_POST[$key] ?? $default;
    }

    public static function isJsonRequest()
    {
        return isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }
}
