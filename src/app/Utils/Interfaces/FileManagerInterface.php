<?php

namespace App\Utils\Interfaces;

interface FileManagerInterface
{
    public function createDirectory($dirname, $parentPath);
    public function moveUploadedFile($tmpFilePath, $uploadDir, $fileName);
    public function deleteFile($filePath);
    public function deleteDirectoryRecursively($directory);
    public function getUploadPath($parentPath = '');
}
