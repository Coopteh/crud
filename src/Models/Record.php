<?php
namespace App\Models;
use PDO;
use PDOException;
use RuntimeException;
class Record {
    private PDO $pdo;

    public function __construct()
    {
        $host = '127.0.0.1';
        $db   = 'example1';
        $user = 'root';      // Замените на ваши данные
        $pass = '';          // Замените на ваши данные
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // В продакшене логируйте ошибку, а пользователю показывайте заглушку
            throw new RuntimeException('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
    }

    /**
     * Получает все записи из таблицы table1
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM table1');
        return $stmt->fetchAll();
    }
}