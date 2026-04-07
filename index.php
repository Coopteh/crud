<?php
// Подключение автозагрузчика Composer
require_once __DIR__ . '/vendor/autoload.php';

use Crud\Controllers\RecordController;

// Настройка соединения с БД через PDO [[14]]
$dsn = 'mysql:host=localhost;dbname=example1;charset=utf8mb4';
$username = 'root';
$password = ''; // По умолчанию в XAMPP пароль пустой

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Обработка GET-запроса
    $controller = new RecordController($pdo);
    $controller->index();
    
} catch (PDOException $e) {
    http_response_code(500);
    echo "Ошибка подключения к БД: " . htmlspecialchars($e->getMessage());
}