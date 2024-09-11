<?php
$app = require $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

try {
    $app->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}
