<?php
namespace Crud\Model;
use PDO;
class Record{
    private PDO $pdo;

    public function __construct(){
        $host = 'localhost';
        $db   = 'example1';
        $user = 'root';      // Замените на вашего пользователя БД
        $pass = '';          // Замените на пароль
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset; port=3307";

        $this->pdo = new PDO($dsn, $user, $pass );
        
    }

    public function getAll(): array{
        $stmt = $this->pdo->query('SELECT * FROM table1');
        return $stmt->fetchAll();
    }
}