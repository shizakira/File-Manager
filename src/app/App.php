<?php

namespace App;

use App\Factories\ControllerFactory;
use Core\Router;
use Core\Config;
use Core\Response;

class App
{
    private $router;
    private $controllerFactory;
    private $config;

    public function __construct($router, $controllerFactory)
    {
        $this->config = Config::getInstance();
        $this->router = $router;
        $this->controllerFactory = $controllerFactory;
        $this->initializeRoutes();
    }

    private function initializeRoutes()
    {
        $controller = $this->controllerFactory->createFileController();

        $routes = [
            'ROUTE_INDEX' => 'index',
            'ROUTE_ADD_DIRECTORY' => 'addDirectory',
            'ROUTE_UPLOAD_FILE' => 'uploadFile',
            'ROUTE_DELETE_ITEM' => 'deleteItem',
            'ROUTE_DOWNLOAD' => 'download'
        ];

        foreach ($routes as $envKey => $method) {
            $this->router->add($this->config->getEnv($envKey), function () use ($controller, $method) {
                return $controller->$method();
            });
        }
    }

    public function run()
    {
        $response = $this->router->dispatch($_SERVER['REQUEST_URI']);
        $responseType = $this->determineResponseType($response);

        match ($responseType) {
            'view' => Response::render($response['view'], $response['data']),
            'file' => Response::download($response['filePath']),
            'message', 'error' => Response::sendJson($response, $response['statusCode']),
            default => $this->handleUnknownResponseType(),
        };
    }

    private function determineResponseType($response)
    {
        return match (true) {
            isset($response['view'], $response['data']) => 'view',
            isset($response['filePath'], $response['fileName']) => 'file',
            isset($response['message'], $response['statusCode']) => 'message',
            isset($response['error'], $response['statusCode']) => 'error',
            default => 'unknown',
        };
    }

    private function handleUnknownResponseType()
    {
        Response::sendJson(["error" => "Неизвестный тип ответа"], 500);
    }
}
