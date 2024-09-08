<?php

namespace App\Services;

use App\Repositories\Interfaces\FileRepositoryInterface;
use Core\Validator;

class FileService
{
    private $fileRepository;
    private $validator;

    public function __construct($fileRepository, $validator)
    {
        $this->fileRepository = $fileRepository;
        $this->validator = $validator;
    }

    public function getFileTree()
    {
        $files = $this->fileRepository->getFiles();
        return $this->buildTree($files);
    }

    public function addDirectory($dirname, $parentId)
    {
        if (!$this->validator->validateName($dirname)) {
            return "Имя каталога не может превышать " . Validator::MAX_NAME_LENGTH . " символов.";
        }

        $parentPath = $this->getParentPath($parentId);
        $directoryPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $parentPath . $dirname;

        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true)) {
                return "Ошибка при создании каталога.";
            }
        }

        $this->fileRepository->addDirectory($dirname, $parentId);
        return true;
    }

    public function uploadFile($fileName, $parentId, $tmpFilePath)
    {
        if (!$this->validator->validateFileSize($tmpFilePath)) {
            return "Размер файла не должен превышать " . (Validator::MAX_FILE_SIZE / 1024 / 1024) . " МБ.";
        }

        if (!$this->validator->validateName($fileName)) {
            return "Имя файла не может превышать " . Validator::MAX_NAME_LENGTH . " символов.";
        }

        if (!$this->validator->validateExtension($fileName)) {
            return "Неверный формат файла. Разрешены только " . implode(', ', Validator::ALLOWED_EXTENSIONS);
        }

        $parentPath = $this->getParentPath($parentId);
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $parentPath;
        $filePath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                return "Ошибка при создании каталога для файла.";
            }
        }

        if (!move_uploaded_file($tmpFilePath, $filePath)) {
            return "Ошибка при загрузке файла.";
        }

        $this->fileRepository->uploadFile($fileName, $parentId);
        return true;
    }

    public function deleteItem($id)
    {
        $item = $this->fileRepository->getItemById($id);

        if (!$item) {
            return "Элемент не найден.";
        }

        $itemPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $this->getParentPath($item['parent_id']) . $item['name'];

        if ($item['type'] === 'file') {
            if (file_exists($itemPath) && !unlink($itemPath)) {
                return "Ошибка при удалении файла.";
            }
        } elseif ($item['type'] === 'directory') {
            $this->deleteDirectoryRecursively($itemPath);
        }

        $this->fileRepository->deleteItem($id);
        return true;
    }

    private function deleteDirectoryRecursively($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $items = scandir($directory);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $directory . DIRECTORY_SEPARATOR . $item;

            if (is_dir($itemPath)) {
                $this->deleteDirectoryRecursively($itemPath);
            } else {
                unlink($itemPath);
            }
        }

        rmdir($directory);
    }

    private function getParentPath($parentId)
    {
        if ($parentId === null) {
            return '';
        }

        $path = [];
        $currentId = $parentId;

        while ($currentId !== null) {
            $parent = $this->fileRepository->getItemById($currentId);
            if ($parent) {
                $path[] = $parent['name'] . '/';
                $currentId = $parent['parent_id'];
            } else {
                break;
            }
        }

        return implode('', array_reverse($path));
    }

    private function buildTree($items, $parent_id = null)
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] === $parent_id) {
                $children = $this->buildTree($items, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }
}
