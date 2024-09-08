<?php

namespace App\Services;

use App\Repositories\Interfaces\FileRepositoryInterface;
use Core\Validator;

class FileService
{
    private $fileRepository;
    private $validator;

    public function __construct(FileRepositoryInterface $fileRepository, Validator $validator)
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

        $this->fileRepository->addDirectory($dirname, $parentId);
        return true;
    }

    public function uploadFile($fileName, $parentId, $tmpFilePath)
    {
        if (!$this->validator->validateName($fileName)) {
            return "Имя файла не может превышать " . Validator::MAX_NAME_LENGTH . " символов.";
        }

        if (!$this->validator->validateExtension($fileName)) {
            return "Неверный формат файла. Разрешены только " . implode(', ', Validator::ALLOWED_EXTENSIONS);
        }

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $fileName;

        if (!move_uploaded_file($tmpFilePath, $filePath)) {
            return "Ошибка при загрузке файла";
        }

        $this->fileRepository->uploadFile($fileName, $parentId);
        return true;
    }

    public function deleteItem($id)
    {
        $this->fileRepository->deleteItem($id);
        return true;
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
