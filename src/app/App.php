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
        $this->router->add('/', function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->index();
        });

        $this->router->add('/add_directory', function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->addDirectory();
        });

        $this->router->add('/upload_file', function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->uploadFile();
        });

        $this->router->add('/delete_item', function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->deleteItem();
        });

        $this->router->add('/download', function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->download();
        });
    }

    public function run()
    {
        $this->router->dispatch($_SERVER['REQUEST_URI']);
    }
}
