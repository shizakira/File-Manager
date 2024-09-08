<?php

namespace App;

use App\Factories\ControllerFactory;
use Core\Router;

class App
{
    private $router;
    private $controllerFactory;

    public function __construct(Router $router, ControllerFactory $controllerFactory)
    {
        $this->router = $router;
        $this->controllerFactory = $controllerFactory;
        $this->initializeRoutes();
    }

    private function initializeRoutes()
    {
        $this->router->add('/', [$this->controllerFactory->createFileController(), 'index']);
        $this->router->add('/add_directory', [$this->controllerFactory->createFileController(), 'addDirectory']);
        $this->router->add('/upload_file', [$this->controllerFactory->createFileController(), 'uploadFile']);
        $this->router->add('/delete_item', [$this->controllerFactory->createFileController(), 'deleteItem']);
        $this->router->add('/download', [$this->controllerFactory->createFileController(), 'download']);
    }

    public function run()
    {
        $this->router->dispatch($_SERVER['REQUEST_URI']);
    }
}
