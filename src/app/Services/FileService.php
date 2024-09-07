<?php

namespace App\Services;

class FileService
{
    private $fileRepository;

    public function __construct($fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function getFileTree()
    {
        return $this->fileRepository->buildTree();
    }

    public function addDirectory($dirname, $parentId)
    {
        $this->fileRepository->addDirectory($dirname, $parentId);
    }

    public function uploadFile($fileName, $parentId)
    {
        $this->fileRepository->uploadFile($fileName, $parentId);
    }

    public function deleteItem($id)
    {
        $this->fileRepository->deleteItem($id);
    }
}
