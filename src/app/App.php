<?php

namespace App;

use Core\Config;
use Core\Response;
use App\Controllers\FileController;

class App
{
    private Config $config;

    public function __construct(
        private $router,
        private $container
    ) {
        $this->config = Config::getInstance();
        $this->initializeRoutes();
    }

    private function initializeRoutes(): void
    {
        $controller = $this->container->get(FileController::class);

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

    public function run(): void
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

    private function determineResponseType(array $response): string
    {
        return match (true) {
            isset($response['view'], $response['data']) => 'view',
            isset($response['filePath'], $response['fileName']) => 'file',
            isset($response['message'], $response['statusCode']) => 'message',
            isset($response['error'], $response['statusCode']) => 'error',
            default => 'unknown',
        };
    }

    private function handleUnknownResponseType(): void
    {
        Response::sendJson(["error" => "Неизвестный тип ответа"], 500);
    }
}
