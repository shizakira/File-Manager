<?php

namespace App\Repositories;

use Core\Database;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Factories\ModelFactory;
use App\Models\AbstractModel;

class FileRepository implements FileRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    private function executeQuery(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getFiles(): array
    {
        $sql = "SELECT * FROM files";
        $stmt = $this->executeQuery($sql);
        $results = $stmt->fetchAll();

        return array_map([ModelFactory::class, 'createModel'], $results);
    }

    public function getItemById(int $id): ?AbstractModel
    {
        $sql = "SELECT * FROM files WHERE id = :id";
        $stmt = $this->executeQuery($sql, ['id' => $id]);
        $result = $stmt->fetch();

        return $result ? ModelFactory::createModel($result) : null;
    }

    public function addDirectory(string $name, ?int $parentId): void
    {
        $sql = "INSERT INTO files (name, type, parent_id) VALUES (:name, 'directory', :parent_id)";
        $this->executeQuery($sql, ['name' => $name, 'parent_id' => $parentId]);
    }

    public function uploadFile(string $name, ?int $parentId): void
    {
        $sql = "INSERT INTO files (name, type, parent_id) VALUES (:name, 'file', :parent_id)";
        $this->executeQuery($sql, ['name' => $name, 'parent_id' => $parentId]);
    }

    public function deleteItem(int $id): void
    {
        $sql = "DELETE FROM files WHERE id = :id";
        $this->executeQuery($sql, ['id' => $id]);
    }
}
