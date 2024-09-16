<?php

use App\Services\FileService;
use App\Repositories\FileRepository;
use App\Utils\FileManager;
use App\Validators\FileValidator;
use App\Views\Renderers\FileViewRenderer;
use App\Controllers\FileController;

use function DI\create;
use function DI\get;

return [
    FileRepository::class => create(FileRepository::class),
    FileManager::class => create(FileManager::class),
    FileValidator::class => create(FileValidator::class),
    FileViewRenderer::class => create(FileViewRenderer::class),
    FileService::class => create(FileService::class)->constructor(get(FileRepository::class), get(FileManager::class)),
    FileController::class => create(FileController::class)
        ->constructor(get(FileService::class), get(FileViewRenderer::class), get(FileValidator::class)),
];
