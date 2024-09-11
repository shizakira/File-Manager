<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use App\App;
use App\Factories\ControllerFactory;
use Core\Router;

$router = new Router();
$controllerFactory = new ControllerFactory();
$app = new App($router, $controllerFactory);

return $app;
