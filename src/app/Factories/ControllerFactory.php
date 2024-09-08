<?php

namespace App\Factories;

use App\Controllers\FileController;
use App\Services\FileService;
use App\Views\FileViewRenderer;
use App\Repositories\FileRepository;
use Core\Validator;

class ControllerFactory
{
    public function createFileController(): FileController
    {
        $validator = new Validator();
        $fileRepository = new FileRepository();
        $fileService = new FileService($fileRepository, $validator);
        $fileViewRenderer = new FileViewRenderer();

        return new FileController($fileService, $fileViewRenderer);
    }
}
