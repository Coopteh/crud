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
        $host    = '127.0.0.1';
        $db      = 'exam06';
        $user    = 'root';
        $pass    = '';
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
            throw new RuntimeException('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM events WHERE is_deleted = 0 ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id = :id AND is_deleted = 0');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function insert(array $data): bool
    {
        $data = array_filter($data, fn($k) => !in_array($k, ['id', 'is_deleted']), ARRAY_FILTER_USE_KEY);

        if (empty($data)) {
            return false;
        }

        $columns      = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql  = "INSERT INTO events ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data = array_filter($data, fn($k) => !in_array($k, ['id', 'is_deleted']), ARRAY_FILTER_USE_KEY);

        if (empty($data)) {
            return false;
        }

        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "`$key` = :$key";
        }
        $set = implode(', ', $setParts);

        $sql = "UPDATE events SET $set WHERE id = :id AND is_deleted = 0";
        $data['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE events SET is_deleted = 1 WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Возвращает список полей для формы.
     * Исключаем ТОЛЬКО служебные поля id и is_deleted.
     */
    public function getFormFields(): array
    {
        $stmt    = $this->pdo->query('DESCRIBE events');
        $columns = $stmt->fetchAll();

        $fields = [];
        foreach ($columns as $col) {
            // Исключаем только служебные поля
            if (in_array($col['Field'], ['id', 'is_deleted'])) {
                continue;
            }
            $fields[] = [
                'name'    => $col['Field'],
                'type'    => $col['Type'],
                'null'    => $col['Null'],
                'default' => $col['Default'],
            ];
        }
        return $fields;
    }
}