<?php

namespace Core;

class Response
{
    public static function send($content, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo $content;
    }

    public static function render($viewPath, $data = [])
    {
        extract($data);
        include($viewPath);
    }

    public static function download($filePath, $fileName)
    {
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        } else {
            self::send("Файл не найден", 404);
        }
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
