<?php

namespace App\Controllers;

use App\Services\FileService;
use App\Views\FileViewRenderer;
use Core\Validator;

class FileController
{
    private $fileService;
    private $fileViewRenderer;
    private $validator;

    private const INDEX_VIEW_PATH = '/app/Views/index.php';
    private const UPLOAD_PATH = '/uploads/';

    public function __construct(FileService $fileService, FileViewRenderer $fileViewRenderer, Validator $validator)
    {
        $this->fileService = $fileService;
        $this->fileViewRenderer = $fileViewRenderer;
        $this->validator = $validator;
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
        $dirname = $_POST['dirname'] ?? '';
        $parentIdInput = $_POST['parent_id'] ?? null;
        $isValidParentId = isset($parentIdInput) && $parentIdInput !== 'null';
        $parentId = $isValidParentId ? (int)$parentIdInput : null;

        if (!$dirname) {
            http_response_code(400);
            return "Ошибка: Имя каталога не указано.";
        }

        if (!$this->validator->validateName($dirname)) {
            http_response_code(400);
            return "Ошибка: Имя каталога не может превышать 255 символов.";
        }

        $this->fileService->addDirectory($dirname, $parentId);
        return "Каталог добавлен";
    }

    public function uploadFile()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            return "Файл не загружен";
        }

        $fileName = $_FILES['file']['name'];

        if (!$this->validator->validateName($fileName)) {
            http_response_code(400);
            return "Ошибка: Имя файла не может превышать 255 символов.";
        }

        if (!$this->validator->validateExtension($fileName)) {
            http_response_code(400);
            return "Ошибка: Неверный формат файла. Разрешены только .jpg, .jpeg, .png, .gif, .txt, .docx, .pdf";
        }

        $filePath = $_SERVER['DOCUMENT_ROOT'] . self::UPLOAD_PATH . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            http_response_code(500);
            return "Ошибка при загрузке файла";
        }

        $parentId = $_POST['parent_id'] ?? null;
        $this->fileService->uploadFile($fileName, $parentId);
        return "Файл успешно загружен";
    }


    public function deleteItem()
    {
        $id = $_POST['id'];
        if (!$id) {
            http_response_code(400);
            return "Ошибка: ID не указан.";
        }

        $this->fileService->deleteItem($id);
        return "Элемент удален";
    }


    public function download()
    {
        $fileName = $_GET['filename'] ?? '';

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
