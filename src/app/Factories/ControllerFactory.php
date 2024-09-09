<?php

namespace App\Factories;

use App\Controllers\FileController;
use App\Services\FileService;
use App\Services\FileManager;
use App\Views\FileViewRenderer;
use App\Repositories\FileRepository;
use Core\Validator;

class ControllerFactory
{
    public function createFileController(): FileController
    {
        $validator = new Validator();
        $fileRepository = new FileRepository();
        $fileManager = new FileManager();
        $fileService = new FileService($fileRepository, $validator, $fileManager);
        $fileViewRenderer = new FileViewRenderer();

        return new FileController($fileService, $fileViewRenderer);
    }
}
