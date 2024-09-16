<?php

namespace App\Validators;

use App\Validators\Interfaces\FileValidatorInterface;
use Core\Config;

class FileValidator implements FileValidatorInterface
{
    private Config $config;

    public function __construct()
    {

        $this->config = Config::getInstance();
    }

    public function validateName(string $name): ?string
    {
        if (mb_strlen($name) > $this->config::MAX_NAME_LENGTH) {
            return "Имя не может превышать " . $this->config::MAX_NAME_LENGTH . " символов.";
        }

        return null;
    }

    public function validateExtension(string $fileName): ?string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($extension, $this->config::ALLOWED_EXTENSIONS, true)) {
            $allowedExtensions = implode(', ', $this->config::ALLOWED_EXTENSIONS);

            return "Неверный формат файла. Разрешены только " . $allowedExtensions;
        }

        return null;
    }

    public function validateFileSize(string $tmpFilePath): ?string
    {
        $fileSize = filesize($tmpFilePath);

        if ($fileSize > $this->config::MAX_FILE_SIZE) {
            $maxFileSize = $this->config::MAX_FILE_SIZE / 1024 / 1024;
            return "Размер файла не должен превышать " . $maxFileSize . " МБ.";
        }

        return null;
    }
}
