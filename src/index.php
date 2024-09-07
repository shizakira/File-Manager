<?php
require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

use App\App;

$app = new App();

try {
    $app->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
