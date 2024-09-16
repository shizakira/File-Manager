<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use App\App;
use Core\Router;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions($_SERVER['DOCUMENT_ROOT'] . '/app/di-config.php');

$container = $containerBuilder->build();

$router = $container->get(Router::class);

$app = new App($router, $container);

return $app;
