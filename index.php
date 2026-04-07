<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Controllers\RecordController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new RecordController();
    $controller->index();
} else {
    http_response_code(405);
    echo 'Метод запроса не поддерживается.';
}