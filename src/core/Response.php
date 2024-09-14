<?php

namespace Core;

class Response
{
    public static function sendJson($content, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($content);
    }

    public static function render($viewPath, $data = [])
    {
        extract($data);
        include($viewPath);
    }

    public static function download($filePath)
    {
        file_exists($filePath) ? readfile($filePath) : self::sendJson(["error" => "Файл не найден"], 404);
    }

    public static function success($message, $statusCode = 200)
    {
        return [
            'message' => $message,
            'statusCode' => $statusCode
        ];
    }

    public static function error($message, $statusCode = 400)
    {
        return [
            'error' => $message,
            'statusCode' => $statusCode
        ];
    }
}
