<?php

namespace App\Factories;

use App\Controllers\FileController;
use App\Services\FileService;
use App\Views\Renderers\FileViewRenderer;
use App\Validators\FileValidator;

class ControllerFactory
{
    protected $fileService;
    protected $fileViewRenderer;
    protected $fileValidator;

    public function __construct(
        FileService $fileService,
        FileViewRenderer $fileViewRenderer,
        FileValidator $fileValidator
    ) {
        $this->fileService = $fileService;
        $this->fileViewRenderer = $fileViewRenderer;
        $this->fileValidator = $fileValidator;
    }

    public function createFileController()
    {
        return new FileController($this->fileService, $this->fileViewRenderer, $this->fileValidator);
    }
}
