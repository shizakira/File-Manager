<?php

namespace App;

use App\Controllers\FileController;
use Core\Router;

class App
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->initializeRoutes();
    }

    private function initializeRoutes()
    {
        $this->router->add('/', [FileController::class, 'index']);
        $this->router->add('/add_directory', [FileController::class, 'addDirectory']);
        $this->router->add('/upload_file', [FileController::class, 'uploadFile']);
        $this->router->add('/delete_item', [FileController::class, 'deleteItem']);
        $this->router->add('/download', [FileController::class, 'download']);
    }

    public function run()
    {
        $this->router->dispatch($_SERVER['REQUEST_URI']);
    }
}
