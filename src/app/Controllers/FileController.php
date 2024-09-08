<?php

namespace App\Controllers;

use App\Services\FileService;
use App\Views\FileViewRenderer;
use App\Models\FileModel;
use Core\Validator;

class FileController
{
    private $fileService;
    private $fileViewRenderer;
    private $validator;
    private $indexPath = '/app/Views/index.php';
    private $uploadPath = '/uploads/';
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

        require $_SERVER['DOCUMENT_ROOT'] . $this->indexPath;
    }

    public function addDirectory()
    {
        $dirname = $_POST['dirname'] ?? '';
        $parentIdInput = $_POST['parent_id'] ?? null;
        $isValidParentId = isset($parentIdInput) && $parentIdInput !== 'null';
        $parentId = $isValidParentId ? (int)$parentIdInput : null;

        if (!$dirname) {
            http_response_code(400);
            echo "Ошибка: Имя каталога не указано.";
            return;
        }

        if (!$this->validator->validateName($dirname)) {
            http_response_code(400);
            echo "Ошибка: Имя каталога не может превышать 255 символов.";
            return;
        }

        $this->fileService->addDirectory($dirname, $parentId);
        echo "Каталог добавлен";
    }

    public function uploadFile()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo "Файл не загружен";
            return;
        }

        $fileName = $_FILES['file']['name'];

        if (!$this->validator->validateName($fileName)) {
            http_response_code(400);
            echo "Ошибка: Имя файла не может превышать 255 символов.";
            return;
        }

        if (!$this->validator->validateExtension($fileName)) {
            http_response_code(400);
            echo "Ошибка: Неверный формат файла. Разрешены только .jpg, .jpeg, .png, .gif, .txt, .docx, .pdf";
            return;
        }

        $filePath = $_SERVER['DOCUMENT_ROOT'] . $this->uploadPath . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            http_response_code(500);
            echo "Ошибка при загрузке файла";
            return;
        }

        $parentId = $_POST['parent_id'] ?? null;
        $this->fileService->uploadFile($fileName, $parentId);
        echo "Файл успешно загружен";
    }

    public function deleteItem()
    {
        $id = $_POST['id'];
        if (!$id) {
            http_response_code(400);
            echo "Ошибка: ID не указан.";
            return;
        }

        $this->fileService->deleteItem($id);
        echo "Элемент удален";
    }

    public function download()
    {
        $fileName = $_GET['filename'] ?? '';

        if (!$fileName) {
            http_response_code(400);
            echo "Ошибка: Имя файла не указано.";
            return;
        }

        $filePath = $_SERVER['DOCUMENT_ROOT'] . $this->uploadPath . basename($fileName);

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "Ошибка: Файл не найден.";
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
