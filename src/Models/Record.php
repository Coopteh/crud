<?php
namespace Crud\Models;

use PDO;

class Record
{
    public function getAll(): array
    {
        $pdo = new PDO(
    'mysql:host=127.0.0.1:3307;dbname=example1;charset=utf8mb4',
    'root', '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
        return $pdo->query('SELECT id, name FROM table1 ORDER BY id')->fetchAll();
    }
}