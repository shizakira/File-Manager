<?php

namespace Core;

use PDO;
use PDOException;
use Core\Traits\Singleton;
use Core\Config;

class Database
{
    use Singleton;

    private PDO $pdo;

    protected string $driver = "";
    protected string $host = "";
    protected string $dbName = "";
    protected string $password = "";
    protected string $user = "";

    protected function init(): void
    {
        $this->loadConfiguration();
        $this->makeConnect();
    }

    protected function loadConfiguration(): void
    {
        $config = Config::getInstance();
        $this->driver = $config->getEnv("DB_DRIVER");
        $this->host = $config->getEnv("DB_HOST");
        $this->dbName = $config->getEnv("DB_DATABASE");
        $this->password = $config->getEnv("DB_PASSWORD");
        $this->user = $config->getEnv("DB_USER");
    }

    protected function makeConnect(): void
    {
        $dsn = "{$this->driver}:dbname={$this->dbName};host={$this->host}";

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
