<?php

namespace Core;

use Dotenv\Dotenv;
use Core\Traits\Singleton;

class Config
{
    use Singleton;

    protected function init()
    {
        $this->loadEnv();
    }

    protected function loadEnv()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }


    public function getEnv($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
