<?php

namespace App;

use App\Factories\ControllerFactory;
use Core\Router;
use Core\Config;

class App
{
    private $router;
    private $controllerFactory;
    private $config;

    public function __construct(Router $router, ControllerFactory $controllerFactory)
    {
        $this->config = Config::getInstance();
        $this->router = $router;
        $this->controllerFactory = $controllerFactory;
        $this->initializeRoutes();
    }

    private function initializeRoutes()
    {
        $this->router->add($this->config->getEnv('ROUTE_INDEX'), function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->index();
        });

        $this->router->add($this->config->getEnv('ROUTE_ADD_DIRECTORY'), function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->addDirectory();
        });

        $this->router->add($this->config->getEnv('ROUTE_UPLOAD_FILE'), function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->uploadFile();
        });

        $this->router->add($this->config->getEnv('ROUTE_DELETE_ITEM'), function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->deleteItem();
        });

        $this->router->add($this->config->getEnv('ROUTE_DOWNLOAD'), function () {
            $controller = $this->controllerFactory->createFileController();
            return $controller->download();
        });
    }

    public function run()
    {
        $this->router->dispatch($_SERVER['REQUEST_URI']);
    }
}
