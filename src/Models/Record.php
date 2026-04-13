<?php

namespace App\Models;

use PDO;
use PDOException;
use RuntimeException;

class Record
{
    private PDO $pdo;

    public function __construct()
    {
        $host = '127.0.0.1';
        $db   = 'is231';
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
        $stmt = $this->pdo->query('SELECT * FROM products WHERE is_deleted = 0');
        return $stmt->fetchAll();
    }
    /**
     * Получает одну запись по ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id_product = :id AND is_deleted = 0');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Вставляет новую запись
     */
    public function insert(array $data): bool
    {
        // Исключаем id и is_deleted из вставки
        $data = array_filter($data, fn($k) => !in_array($k, ['id_product', 'is_deleted']), ARRAY_FILTER_USE_KEY);
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO products ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Обновляет существующую запись
     */
    public function update(int $id, array $data): bool
{
    $data = array_filter($data, fn($k) => !in_array($k, ['id_product', 'is_deleted']), ARRAY_FILTER_USE_KEY);
    
    $setParts = [];
    foreach (array_keys($data) as $key) {
        $setParts[] = "$key = :$key";
    }
    $set = implode(', ', $setParts);
    
    $sql = "UPDATE products SET $set WHERE id_product = :id_product AND is_deleted = 0";
    $data['id_product'] = $id;
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($data);
    }

    /**
     * Мягкое удаление (пометка is_deleted = 1)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE products SET is_deleted = 1 WHERE id_product = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Возвращает список колонок таблицы (кроме служебных)
     */
    public function getFormFields(): array
    {
        $stmt = $this->pdo->query('DESCRIBE products');
        $columns = $stmt->fetchAll();
        
        $fields = [];
        foreach ($columns as $col) {
            if (!in_array($col['Field'], ['id_product', 'is_deleted', 'created_at', 'updated_at'])) {
                $fields[] = [
                    'name' => $col['Field'],
                    'type' => $col['Type'],
                    'null' => $col['Null'],
                    'default' => $col['Default']
                ];
            }
        }
        return $fields;
    }
}

