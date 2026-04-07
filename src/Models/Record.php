<?php
namespace Crud\Models;

use PDO;

class Record
{
    private PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=example1;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    
    public function getAll(): array
    {
        return $this->pdo->query('SELECT id, name FROM table1')->fetchAll();
    }
}