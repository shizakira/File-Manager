<?php

namespace App\Repositories;

use Core\Database;
use App\Repositories\Interfaces\FileRepositoryInterface;

class FileRepository implements FileRepositoryInterface
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    private function executeQuery($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function getFiles()
    {
        $sql = "SELECT * FROM files";
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll();
    }

    public function addDirectory($name, $parent_id)
    {
        $sql = "INSERT INTO files (name, type, parent_id) VALUES (:name, 'directory', :parent_id)";
        $this->executeQuery($sql, ['name' => $name, 'parent_id' => $parent_id]);
    }

    public function uploadFile($name, $parent_id)
    {
        $sql = "INSERT INTO files (name, type, parent_id) VALUES (:name, 'file', :parent_id)";
        $this->executeQuery($sql, ['name' => $name, 'parent_id' => $parent_id]);
    }

    public function deleteItem($id)
    {
        $sql = "DELETE FROM files WHERE id = :id";
        $this->executeQuery($sql, ['id' => $id]);
    }
}
