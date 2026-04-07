<?php

require_once __DIR__ . '/src/database.php';

// Создание таблицы
$pdo->exec("CREATE TABLE IF NOT EXISTS records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL
)");

// Очистка и вставка записей
$stmt = $pdo->prepare("INSERT INTO records (name) VALUES (?)");
$stmt->execute(['Земля']);
$stmt->execute(['Луна']);
$stmt->execute(['Марс']);

echo "Таблица создана и заполнена!";