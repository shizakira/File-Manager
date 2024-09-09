<?php

namespace Core;

use PDO;
use PDOException;
use Core\Traits\Singleton;
use Core\Config;

class Database
{
    use Singleton;

    private $pdo;

    protected $driver = "";
    protected $host = "";
    protected $dbName = "";
    protected $password = "";
    protected $user = "";

    protected function init()
    {
        $this->loadConfiguration();
        $this->makeConnect();
    }

    protected function loadConfiguration()
    {
        $config = Config::getInstance();
        $this->driver = $config->getEnv("DB_DRIVER");
        $this->host = $config->getEnv("DB_HOST");
        $this->dbName = $config->getEnv("DB_DATABASE");
        $this->password = $config->getEnv("DB_PASSWORD");
        $this->user = $config->getEnv("DB_USER");
    }

    protected function makeConnect()
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

    public function getConnection()
    {
        return $this->pdo;
    }
}
