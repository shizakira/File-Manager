<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;

class FileController
{
    private $fileService;
    private $fileViewRenderer;

    private const INDEX_VIEW_PATH = '/app/Views/index.php';
    private const UPLOAD_PATH = '/uploads';

    public function __construct($fileService, $fileViewRenderer)
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
            return Response::error("Имя каталога не указано.", 400);
        }

        $result = $this->fileService->addDirectory($dirname, $parentId);

        if ($result !== true) {
            return Response::error($result, 400);
        }

        return Response::success("Каталог добавлен");
    }

    public function uploadFile()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            return Response::error("Файл не загружен", 400);
        }

        $fileName = $_FILES['file']['name'];
        $parentId = Request::post('parent_id');

        $result = $this->fileService->uploadFile($fileName, $parentId, $_FILES['file']['tmp_name']);

        if ($result !== true) {
            return Response::error($result, 400);
        }

        return Response::success("Файл успешно загружен");
    }

    public function deleteItem()
    {
        $id = Request::post('id');

        if (!$id) {
            return Response::error("Ошибка: ID не указан.", 400);
        }

        $result = $this->fileService->deleteItem($id);

        if ($result !== true) {
            return Response::error($result, 400);
        }

        return Response::success("Элемент удален");
    }

    public function download()
    {
        $fileName = Request::get('filename');

        if (!$fileName) {
            return Response::error("Ошибка: Имя файла не указано.", 400);
        }

        $filePath = $_SERVER['DOCUMENT_ROOT'] . self::UPLOAD_PATH . DIRECTORY_SEPARATOR . basename($fileName);

        if (!file_exists($filePath)) {
            return Response::error("Ошибка: Файл не найден.", 404);
        }

        return [
            'filePath' => $filePath,
            'fileName' => basename($filePath)
        ];
    }
}
