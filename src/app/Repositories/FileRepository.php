<?php

namespace App\Repositories;

use Core\Database;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Models\FileModel;
use App\Models\DirectoryModel;

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

    private function createModelFromRow($row)
    {
        if ($row['type'] === 'directory') {
            return new DirectoryModel($row['id'], $row['name'], $row['parent_id']);
        }

        return new FileModel($row['id'], $row['name'], $row['parent_id']);
    }


    public function getFiles()
    {
        $sql = "SELECT * FROM files";
        $stmt = $this->executeQuery($sql);
        $results = $stmt->fetchAll();

        return array_map([$this, 'createModelFromRow'], $results);
    }


    public function getItemById($id)
    {
        $sql = "SELECT * FROM files WHERE id = :id";
        $stmt = $this->executeQuery($sql, ['id' => $id]);
        $result = $stmt->fetch();

        if ($result) {
            return $this->createModelFromRow($result);
        }

        return null;
    }

    public function addDirectory($name, $parentId)
    {
        $sql = "INSERT INTO files (name, type, parent_id) VALUES (:name, 'directory', :parent_id)";
        $this->executeQuery($sql, ['name' => $name, 'parent_id' => $parentId]);
    }

    public function uploadFile($name, $parentId)
    {
        $sql = "INSERT INTO files (name, type, parent_id) VALUES (:name, 'file', :parent_id)";
        $this->executeQuery($sql, ['name' => $name, 'parent_id' => $parentId]);
    }

    public function deleteItem($id)
    {
        $sql = "DELETE FROM files WHERE id = :id";
        $this->executeQuery($sql, ['id' => $id]);
    }
}
