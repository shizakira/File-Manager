<?php

namespace App\Controllers;

use App\Services\FileService;
use App\Views\FileViewRenderer;
use Core\Request;

class FileController
{
    private FileService $fileService;
    private FileViewRenderer $fileViewRenderer;

    private const INDEX_VIEW_PATH = '/app/Views/index.php';
    private const UPLOAD_PATH = '/uploads/';

    public function __construct(FileService $fileService, FileViewRenderer $fileViewRenderer)
    {
        $this->fileService = $fileService;
        $this->fileViewRenderer = $fileViewRenderer;
    }

    public function index()
    {
        $filesTree = $this->fileService->getFileTree();
        $treeHtml = $this->fileViewRenderer->renderTree($filesTree);

        return [
            'view' => $_SERVER['DOCUMENT_ROOT'] . self::INDEX_VIEW_PATH,
            'data' => compact('treeHtml')
        ];
    }

    public function addDirectory()
    {
        $dirname = Request::post('dirname');
        $parentIdInput = Request::post('parent_id');
        $isValidParentId = isset($parentIdInput) && $parentIdInput !== 'null';
        $parentId = $isValidParentId ? (int)$parentIdInput : null;

        if (!$dirname) {
            http_response_code(400);
            return "Имя каталога не указано.";
        }

        $result = $this->fileService->addDirectory($dirname, $parentId);

        if ($result !== true) {
            http_response_code(400);
            return $result;
        }

        return "Каталог добавлен";
    }

    public function uploadFile()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            return "Файл не загружен";
        }

        $fileName = $_FILES['file']['name'];
        $parentId = Request::post('parent_id');

        $result = $this->fileService->uploadFile($fileName, $parentId, $_FILES['file']['tmp_name']);

        if ($result !== true) {
            http_response_code(400);
            return $result;
        }

        return "Файл успешно загружен";
    }

    public function deleteItem()
    {
        $id = Request::post('id');
        if (!$id) {
            http_response_code(400);
            return "Ошибка: ID не указан.";
        }

        $result = $this->fileService->deleteItem($id);

        if ($result !== true) {
            http_response_code(400);
            return $result;
        }

        return "Элемент удален";
    }

    public function download()
    {
        $fileName = Request::get('filename');

        if (!$fileName) {
            http_response_code(400);
            return "Ошибка: Имя файла не указано.";
        }

        $filePath = $_SERVER['DOCUMENT_ROOT'] . self::UPLOAD_PATH . basename($fileName);

        if (!file_exists($filePath)) {
            http_response_code(404);
            return "Ошибка: Файл не найден.";
        }

        return [
            'filePath' => $filePath,
            'fileName' => basename($filePath)
        ];
    }
}
