<?php

namespace Core;

class Response
{
    public static function sendJson(array $content, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($content);
    }

    public static function render(string $viewPath, array $data = []): void
    {
        extract($data);
        include($viewPath);
    }

    public static function download(string $filePath): void
    {
        file_exists($filePath) ? readfile($filePath) : self::sendJson(["error" => "Файл не найден"], 404);
    }

    public static function success(string $message, int $statusCode = 200): array
    {
        return [
            'message' => $message,
            'statusCode' => $statusCode
        ];
    }

    public static function error(string $message, int $statusCode = 400): array
    {
        return [
            'error' => $message,
            'statusCode' => $statusCode
        ];
    }
}
