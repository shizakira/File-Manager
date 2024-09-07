<?php

namespace App\Models;

use Core\Database;
use App\Repositories\FileRepositoryInterface;

class FileModel implements FileRepositoryInterface
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

    public function buildTree($parent_id = null)
    {
        $items = $this->getFiles();
        $tree = $this->buildTreeRecursive($items, $parent_id);
        return $tree;
    }

    private function buildTreeRecursive($items, $parent_id = null)
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] === $parent_id) {
                $children = $this->buildTreeRecursive($items, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
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
