<?php

namespace Core;

class Validator
{
    public const MAX_NAME_LENGTH = 255;
    public const MAX_FILE_SIZE = 1024 * 1024 * 20;
    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'docx', 'pdf'];

    public function validateName($name)
    {
        return mb_strlen($name) <= self::MAX_NAME_LENGTH;
    }

    public function validateExtension($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }

    public function validateFileSize($tmpFilePath)
    {
        $fileSize = filesize($tmpFilePath);
        return $fileSize <= self::MAX_FILE_SIZE;
    }
}
