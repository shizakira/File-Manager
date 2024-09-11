<?php

namespace App\Validators;

use App\Validators\Interfaces\FileValidatorInterface;

class FileValidator implements FileValidatorInterface
{
    private const MAX_NAME_LENGTH = 255;
    private const MAX_FILE_SIZE = 1024 * 1024 * 20;
    private const ALLOWED_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'txt',
        'docx',
        'pdf'
    ];

    public function validateName($name)
    {
        if (mb_strlen($name) > self::MAX_NAME_LENGTH) {
            return "Имя не может превышать " . self::MAX_NAME_LENGTH . " символов.";
        }

        return;
    }

    public function validateExtension($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $allowedExtensions = implode(', ', self::ALLOWED_EXTENSIONS);
            return "Неверный формат файла. Разрешены только " . $allowedExtensions;
        }

        return;
    }

    public function validateFileSize($tmpFilePath)
    {
        $fileSize = filesize($tmpFilePath);

        if ($fileSize > self::MAX_FILE_SIZE) {
            $maxFileSize = self::MAX_FILE_SIZE / 1024 / 1024;
            return "Размер файла не должен превышать " . $maxFileSize . " МБ.";
        }

        return;
    }
}
