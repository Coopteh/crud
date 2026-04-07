<?php
namespace Crud\Models;

use PDO;
use PDOException;

class Record
{
    private PDO $pdo;
    
    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'example1';
        $user = 'root';
        $pass = '';
        
        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Ошибка подключения: " . $e->getMessage());
        }
    }
    
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM table1 ORDER BY id");
        return $stmt->fetchAll();
    }
}