<?php

namespace App\Utils\Interfaces;

interface FileManagerInterface
{
    public function createDirectory(string $dirname, string $parentPath);
    public function moveUploadedFile(string $tmpFilePath, string $uploadDir, string $fileName);
    public function deleteFile(string $filePath);
    public function deleteDirectoryRecursively(string $directory);
    public function getUploadPath(string $parentPath = '');
}
