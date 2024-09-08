<?php

namespace App\Factories;

use App\Controllers\FileController;
use App\Services\FileService;
use App\Views\FileViewRenderer;
use App\Models\FileModel;
use Core\Validator;

class ControllerFactory
{
    public function createFileController(): FileController
    {
        $fileModel = new FileModel();
        $fileService = new FileService($fileModel);
        $fileViewRenderer = new FileViewRenderer();
        $validator = new Validator();

        return new FileController($fileService, $fileViewRenderer, $validator);
    }
}
