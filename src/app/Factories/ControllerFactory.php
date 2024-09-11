<?php

namespace App\Factories;

use App\Controllers\FileController;
use App\Services\FileService;
use App\Utils\FileManager;
use App\Views\Renderers\FileViewRenderer;
use App\Repositories\FileRepository;
use App\Validators\FileValidator;

class ControllerFactory
{
    public function createFileController()
    {
        $validator = new FileValidator();
        $fileRepository = new FileRepository();
        $fileManager = new FileManager();
        $fileService = new FileService($fileRepository, $validator, $fileManager);
        $fileViewRenderer = new FileViewRenderer();

        return new FileController($fileService, $fileViewRenderer);
    }
}
