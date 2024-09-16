<?php

namespace Core;

use Dotenv\Dotenv;
use Core\Traits\Singleton;

class Config
{
    use Singleton;

    public const MAX_NAME_LENGTH = 255;
    public const MAX_FILE_SIZE = 1024 * 1024 * 20;
    public const ALLOWED_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'txt',
        'docx',
        'pdf'
    ];

    protected function init(): void
    {
        $this->loadEnv();
    }

    protected function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }


    public function getEnv(string $key, ?string $default = null): ?string
    {
        return $_ENV[$key] ?? $default;
    }
}
