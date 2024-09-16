<?php

namespace App\Utils;

use App\Utils\Interfaces\FileManagerInterface;

class FileManager implements FileManagerInterface
{
    private const UPLOAD_PATH = '/uploads';

    public function getUploadPath(string $parentPath = ''): string
    {
        $currentPath = self::UPLOAD_PATH . DIRECTORY_SEPARATOR . $parentPath;

        return $_SERVER['DOCUMENT_ROOT'] . $currentPath;
    }

    public function createDirectory(string $dirname, string $parentPath): bool
    {
        $directoryPath = $this->getUploadPath($parentPath) . $dirname;

        return is_dir($directoryPath) || mkdir($directoryPath, 0777, true);
    }

    public function moveUploadedFile(string $tmpFilePath, string $uploadDir, string $fileName): bool
    {
        $filePath = $uploadDir . $fileName;

        return (is_dir($uploadDir) || mkdir($uploadDir, 0777, true)) &&
            move_uploaded_file($tmpFilePath, $filePath);
    }

    public function deleteFile(string $filePath): bool
    {
        return !is_file($filePath) || unlink($filePath);
    }

    public function deleteDirectoryRecursively(string $directory): void
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
}
