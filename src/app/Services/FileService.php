<?php

namespace App\Services;

class FileService
{
    private $fileRepository;
    private $validator;
    private $fileManager;

    public function __construct($fileRepository, $validator, $fileManager)
    {
        $this->fileRepository = $fileRepository;
        $this->validator = $validator;
        $this->fileManager = $fileManager;
    }

    public function getFileTree()
    {
        $files = $this->fileRepository->getFiles();

        return $this->buildTree($files);
    }

    public function addDirectory($dirname, $parentId)
    {
        if ($error = $this->validator->validateName($dirname)) {
            return $error;
        }

        $parentPath = $this->getParentPath($parentId);

        if (!$this->fileManager->createDirectory($dirname, $parentPath)) {
            return "Ошибка при создании каталога.";
        }

        $this->fileRepository->addDirectory($dirname, $parentId);

        return true;
    }

    public function uploadFile($fileName, $parentId, $tmpFilePath)
    {
        if ($error = $this->validator->validateFileSize($tmpFilePath)) {
            return $error;
        }

        if ($error = $this->validator->validateName($fileName)) {
            return $error;
        }

        if ($error = $this->validator->validateExtension($fileName)) {
            return $error;
        }

        $parentPath = $this->getParentPath($parentId);
        $uploadDir = $this->fileManager->getUploadPath($parentPath);

        if (!$this->fileManager->moveUploadedFile($tmpFilePath, $uploadDir, $fileName)) {
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

        $parentPath = $this->getParentPath($item->getParentId());
        $itemPath = $this->fileManager->getUploadPath($parentPath) . $item->getName();

        if ($item->getType() === 'file') {
            if (!$this->fileManager->deleteFile($itemPath)) {
                return "Ошибка при удалении файла.";
            }
        } elseif ($item->getType() === 'directory') {
            $this->fileManager->deleteDirectoryRecursively($itemPath);
        }

        $this->fileRepository->deleteItem($id);

        return true;
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
                $path[] = $parent->getName() . DIRECTORY_SEPARATOR;
                $currentId = $parent->getParentId();
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
            $itemId = $item->getId();
            $itemParentId = $item->getParentId();

            if ($itemParentId === $parent_id) {
                $children = $this->buildTree($items, $itemId);
                if ($children) {
                    $item->setChildren($children);
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }
}
