<?php

namespace App\Services;

class FileManager
{
    private const UPLOAD_PATH = '/uploads/';

    public function getUploadPath($parentPath = '')
    {
        return $_SERVER['DOCUMENT_ROOT'] . self::UPLOAD_PATH . $parentPath;
    }

    public function createDirectory($dirname, $parentPath)
    {
        $directoryPath = $this->getUploadPath($parentPath) . $dirname;

        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true)) {
                return false;
            }
        }

        return true;
    }

    public function moveUploadedFile($tmpFilePath, $uploadDir, $fileName)
    {
        $filePath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                return false;
            }
        }

        if (!move_uploaded_file($tmpFilePath, $filePath)) {
            return false;
        }

        return true;
    }

    public function deleteFile($filePath)
    {
        if (file_exists($filePath) && !unlink($filePath)) {
            return false;
        }

        return true;
    }

    public function deleteDirectoryRecursively($directory)
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
